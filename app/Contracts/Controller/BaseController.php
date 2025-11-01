<?php

declare(strict_types=1);

namespace App\Contracts\Controller;

use App\Contracts\Repository\BaseRepository;
use App\Contracts\Transformer\BaseTransformer;
use App\Services\ResponseBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController
{
    protected int $maxPerPage = 100;
    protected int $defaultPerPage = 15;
    protected string $defaultSortField = 'created_at';
    protected string $defaultSortDirection = 'desc';

    protected readonly ResponseBuilder $response;

    public function __construct(
        protected readonly BaseRepository  $repository,
        protected readonly BaseTransformer $transformer,
    ) {
        $this->response = new ResponseBuilder();
    }

    public function index(Request $request): JsonResponse
    {
        $this->beforeIndex($request);

        $includes = $this->parseIncludes($request);
        $filters = $this->parseFilters($request);
        $sorts = $this->parseSort($request);
        $pagination = $this->parsePagination($request);

        $query = $this->repository->newQuery();
        $query = $this->applyEagerLoading($query, $includes);
        $query = $this->applyFiltersToQuery($query, $filters);
        $query = $this->applySortToQuery($query, $sorts);
        $query = $this->customizeQuery($query, $request);

        $results = $query->paginate($pagination['per_page']);
        $this->afterIndex($results, $request);

        $transformed = $this->transformer->transformMany($results);

        return $this->response->success(
            $transformed['data'] ?? $transformed,
            null,
            $this->buildPaginationMeta($results)
        );
    }

    public function show(int|string $id, Request $request): JsonResponse
    {
        try {
            $this->beforeShow($id, $request);

            $includes = $this->parseIncludes($request);
            $query = $this->repository->newQuery();
            $query = $this->applyEagerLoading($query, $includes);

            $resource = $query->findOrFail($id);
            $this->authorizeShow($resource);
            $this->afterShow($resource, $request);

            $transformed = $this->transformer->transformOne($resource);
            return $this->response->success($transformed['data'] ?? $transformed);

        } catch (ModelNotFoundException $e) {
            return $this->response->error(
                'Resource not found',
                Response::HTTP_NOT_FOUND,
                [],
                'NOT_FOUND'
            );
        } catch (\InvalidArgumentException $e) {
            return $this->response->error(
                $e->getMessage(),
                Response::HTTP_BAD_REQUEST,
                [],
                'VALIDATION_ERROR'
            );
        } catch (\Exception $e) {
            return $this->response->error(
                'An error occurred while processing the request',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                config('app.debug') ? ['message' => $e->getMessage()] : [],
                'INTERNAL_ERROR'
            );
        }
    }

    protected function parseIncludes(Request $request): array
    {
        $defaultIncludes = $this->transformer->getDefaultIncludes();
        $requestedIncludes = $request->query('includes', '');

        if (empty($requestedIncludes)) {
            return $defaultIncludes;
        }

        $requestedIncludes = is_array($requestedIncludes)
            ? $requestedIncludes
            : explode(',', $requestedIncludes);

        $requestedIncludes = array_map('trim', $requestedIncludes);
        $requestedIncludes = array_filter($requestedIncludes);
        $this->validateIncludes($requestedIncludes);
        return array_unique(array_merge($defaultIncludes, $requestedIncludes));
    }

    protected function validateIncludes(array $includes): void
    {
        $availableIncludes = $this->transformer->getAvailableIncludes();
        $invalidIncludes = [];

        foreach ($includes as $include) {
            if (!in_array($include, $availableIncludes, true)) {
                $invalidIncludes[] = $include;
            }
        }

        if (!empty($invalidIncludes)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid includes: %s. Available: %s',
                    implode(', ', $invalidIncludes),
                    implode(', ', $availableIncludes)
                )
            );
        }
    }

    protected function parseFilters(Request $request): array
    {
        $filters = $request->query('filter', []);

        if (empty($filters) || !is_array($filters)) {
            return [];
        }

        $validFilters = [];
        $searchableFields = $this->repository->fieldSearchable ?? [];

        foreach ($filters as $field => $value) {
            $baseField = explode('.', $field)[0];

            if (!array_key_exists($field, $searchableFields) && !array_key_exists($baseField, $searchableFields)) {
                continue;
            }

            $operator = $searchableFields[$field] ?? $searchableFields[$baseField] ?? '=';

            if (is_array($value)) {
                $parsedFilter = $this->parseComplexFilter($field, $value, $operator);
                if ($parsedFilter) {
                    $validFilters[] = $parsedFilter;
                }
            } else {
                $validFilters[] = [
                    'field' => $field,
                    'operator' => $operator,
                    'value' => $value,
                ];
            }
        }

        return $validFilters;
    }

    protected function parseComplexFilter(string $field, array $value, string $defaultOperator): ?array
    {
        if (isset($value[0])) {
            return [
                'field' => $field,
                'operator' => 'in',
                'value' => $value,
            ];
        }

        if (isset($value['gte']) || isset($value['lte'])) {
            return [
                'field' => $field,
                'operator' => 'between',
                'value' => [
                    $value['gte'] ?? $value['min'] ?? null,
                    $value['lte'] ?? $value['max'] ?? null,
                ],
            ];
        }

        if (isset($value['gt'])) {
            return ['field' => $field, 'operator' => '>', 'value' => $value['gt']];
        }

        if (isset($value['lt'])) {
            return ['field' => $field, 'operator' => '<', 'value' => $value['lt']];
        }

        if (isset($value['like'])) {
            return ['field' => $field, 'operator' => 'like', 'value' => $value['like']];
        }

        if (isset($value['in'])) {
            return ['field' => $field, 'operator' => 'in', 'value' => $value['in']];
        }

        if (isset($value['not_in'])) {
            return ['field' => $field, 'operator' => 'not_in', 'value' => $value['not_in']];
        }

        return null;
    }

    protected function parseSort(Request $request): array
    {
        if ($sortParam = $request->query('sort')) {
            return $this->parseJsonApiSort($sortParam);
        }

        if ($orderBy = $request->query('order_by')) {
            $direction = $request->query('order_direction', 'asc');
            $direction = in_array($direction, ['asc', 'desc'], true) ? $direction : 'asc';

            $this->validateSortFields([['field' => $orderBy, 'direction' => $direction]]);

            return [['field' => $orderBy, 'direction' => $direction]];
        }

        return [['field' => $this->defaultSortField, 'direction' => $this->defaultSortDirection]];
    }

    protected function parseJsonApiSort(string $sortParam): array
    {
        $sorts = explode(',', $sortParam);
        $validSorts = [];

        foreach ($sorts as $sort) {
            $sort = trim($sort);
            if (empty($sort)) {
                continue;
            }

            $direction = 'asc';
            $field = $sort;

            if (str_starts_with($sort, '-')) {
                $direction = 'desc';
                $field = substr($sort, 1);
            }

            $validSorts[] = ['field' => $field, 'direction' => $direction];
        }

        $this->validateSortFields($validSorts);

        return $validSorts;
    }

    protected function validateSortFields(array $sorts): void
    {
        $sortableFields = $this->repository->sortableFields ?? [];

        if (empty($sortableFields)) {
            return;
        }

        $invalidFields = [];

        foreach ($sorts as $sort) {
            if (!in_array($sort['field'], $sortableFields, true)) {
                $invalidFields[] = $sort['field'];
            }
        }

        if (!empty($invalidFields)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid sort fields: %s. Available: %s',
                    implode(', ', $invalidFields),
                    implode(', ', $sortableFields)
                )
            );
        }
    }

    protected function parsePagination(Request $request): array
    {
        $perPage = (int) $request->query('per_page', $this->defaultPerPage);

        if ($perPage > $this->maxPerPage) {
            $perPage = $this->maxPerPage;
        }

        if ($perPage < 1) {
            $perPage = $this->defaultPerPage;
        }

        return [
            'per_page' => $perPage,
            'page' => (int) $request->query('page', 1),
        ];
    }

    protected function applyEagerLoading(Builder $query, array $includes): Builder
    {
        if (!empty($includes)) {
            $query->with($includes);
        }

        return $query;
    }

    protected function applyFiltersToQuery(Builder $query, array $filters): Builder
    {
        foreach ($filters as $filter) {
            $query = $this->applyFilter($query, $filter['field'], $filter);
        }

        return $query;
    }

    protected function applyFilter(Builder $query, string $field, array $filter): Builder
    {
        $operator = $filter['operator'];
        $value = $filter['value'];

        if (str_contains($field, '.')) {
            return $this->applyNestedFilter($query, $field, $operator, $value);
        }

        return match ($operator) {
            'in' => $query->whereIn($field, is_array($value) ? $value : [$value]),
            'not_in' => $query->whereNotIn($field, is_array($value) ? $value : [$value]),
            'between' => $query->whereBetween($field, $value),
            'like' => $query->where($field, 'like', $value),
            '!=', '<>', '>', '>=', '<', '<=' => $query->where($field, $operator, $value),
            default => $query->where($field, $value),
        };
    }

    protected function applyNestedFilter(Builder $query, string $field, string $operator, mixed $value): Builder
    {
        $parts = explode('.', $field, 2);
        $relation = $parts[0];
        $relationField = $parts[1];

        return $query->whereHas($relation, function ($q) use ($relationField, $operator, $value) {
            $this->applyFilter($q, $relationField, [
                'field' => $relationField,
                'operator' => $operator,
                'value' => $value,
            ]);
        });
    }

    protected function applySortToQuery(Builder $query, array $sorts): Builder
    {
        foreach ($sorts as $sort) {
            $query->orderBy($sort['field'], $sort['direction']);
        }

        return $query;
    }

    protected function buildPaginationMeta($paginator): array
    {
        return [
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
        ];
    }

    protected function beforeIndex(Request $request): void
    {
    }

    protected function afterIndex($results, Request $request): void
    {
    }

    protected function beforeShow(int|string $id, Request $request): void
    {
    }

    protected function afterShow($resource, Request $request): void
    {
    }

    protected function authorizeShow($resource): void
    {
    }

    protected function customizeQuery(Builder $query, Request $request): Builder
    {
        return $query;
    }
}
