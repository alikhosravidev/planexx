<?php

declare(strict_types=1);

namespace App\Contracts\Controller;

use App\Contracts\Repository\BaseRepository;
use App\Contracts\Transformer\BaseTransformer;
use App\Http\Transformers\V1\Admin\KeyValTransformer;
use App\Services\ResponseBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

// TODO: test && refactor
abstract class BaseAPIController
{
    protected int $maxPerPage              = 100;
    protected int $defaultPerPage          = 15;
    protected string $defaultSortField     = 'created_at';
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

        $includes   = $this->parseIncludes($request);
        $withCount  = $this->parseWithCount($request);
        $filters    = $this->parseFilters($request);
        $search     = $this->parseSearch($request);
        $sorts      = $this->parseSort($request);
        $pagination = $this->parsePagination($request);

        $query = $this->repository->newQuery();
        $query = $this->applyEagerLoading($query, $includes);
        $query = $this->applyWithCount($query, $withCount);
        $query = $this->applyFiltersToQuery($query, $filters);
        $query = $this->applySearchToQuery($query, $search);
        $query = $this->applySortToQuery($query, $sorts);
        $query = $this->customizeQuery($query, $request);

        $results = $query->paginate($pagination['per_page']);
        $this->afterIndex($results, $request);

        $this->transformer->setIncludes($includes);
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

            $includes  = $this->parseIncludes($request);
            $withCount = $this->parseWithCount($request);
            $query     = $this->repository->newQuery();
            $query     = $this->applyEagerLoading($query, $includes);
            $query     = $this->applyWithCount($query, $withCount);

            $resource = $query->findOrFail($id);
            $this->authorizeShow($resource);
            $this->afterShow($resource, $request);

            $this->transformer->setIncludes($includes);
            $transformed = $this->transformer->transformOne($resource);

            return $this->response->success($transformed['data'] ?? $transformed);

        } catch (ModelNotFoundException $e) {
            return $this->response->error(
                'Resource not found',
                Response::HTTP_NOT_FOUND,
                [],
                'NOT_FOUND'
            );
        } catch (InvalidArgumentException $e) {
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

    public function keyValList(Request $request, string $field, string $key = 'id'): JsonResponse
    {
        $includes   = $this->parseIncludes($request);
        $filters    = $this->parseFilters($request);
        $search     = $this->parseSearch($request);
        $sorts      = $this->parseSort($request);
        $pagination = $this->parsePagination($request);

        $query = $this->repository->newQuery();
        $query = $this->applyEagerLoading($query, $includes);
        $query = $this->applyFiltersToQuery($query, $filters);
        $query = $this->applySearchToQuery($query, $search);
        $query = $this->applySortToQuery($query, $sorts);
        $query = $this->customizeQuery($query, $request);


        $results     = $query->simplePaginate($pagination['per_page'], [$key, $field]);
        $transformer = resolve(KeyValTransformer::class, ['field' => $field, 'key' => $key]);
        $transformed = $transformer->transformMany($results);
        $transformed = $transformed['data'] ?? $transformed;

        return $this->response->success(
            collect($transformed)->collapseWithKeys()->toArray(),
            null,
            $this->buildPaginationMeta($results)
        );
    }

    protected function parseIncludes(Request $request): array
    {
        $defaultIncludes   = $this->transformer->getDefaultIncludes();
        $requestedIncludes = $request->query('includes', '');

        if (empty($requestedIncludes)) {
            return ['relations' => $defaultIncludes, 'aliases' => []];
        }

        $requestedIncludes = is_array($requestedIncludes)
            ? $requestedIncludes
            : explode(',', $requestedIncludes);

        $requestedIncludes = array_map('trim', $requestedIncludes);
        $requestedIncludes = array_filter($requestedIncludes);

        $relations = [];
        $aliases   = [];

        foreach ($requestedIncludes as $include) {
            if (str_contains($include, ':')) {
                [$relation, $alias] = array_map('trim', explode(':', $include, 2));
                $relations[]        = $relation;
                $aliases[$relation] = $alias;
            } else {
                $relations[] = $include;
            }
        }

        $this->validateIncludes($relations, $aliases);

        return [
            'relations' => array_unique(array_merge($defaultIncludes, $relations)),
            'aliases'   => $aliases,
        ];
    }

    protected function parseWithCount(Request $request): array
    {
        $param = $request->query('withCount', '');

        if (empty($param)) {
            return ['root' => [], 'nested' => []];
        }

        $items = is_array($param) ? $param : explode(',', $param);
        $items = array_values(array_filter(array_map('trim', $items)));

        $root   = [];
        $nested = [];

        foreach ($items as $item) {
            // Support alias via ':' -> convert to ' as '
            $target = $item;
            $alias  = null;

            if (str_contains($item, ':')) {
                [$target, $alias] = array_map('trim', explode(':', $item, 2));
            }

            if (str_contains($target, '.')) {
                [$relation, $sub] = array_map('trim', explode('.', $target, 2));

                if ($relation !== '' && $sub !== '') {
                    $expr              = $alias ? ($sub . ' as ' . $alias) : $sub;
                    $nested[$relation] = array_values(array_unique(array_merge($nested[$relation] ?? [], [$expr])));
                }
            } else {
                $root[] = $alias ? ($target . ' as ' . $alias) : $target;
            }
        }

        return [
            'root'   => array_values(array_unique($root)),
            'nested' => $nested,
        ];
    }

    protected function validateIncludes(array $relations, array $aliases = []): void
    {
        $availableIncludes = $this->transformer->getAvailableIncludes();
        $invalidIncludes   = [];

        foreach ($relations as $relation) {
            $aliasOrRelation = $aliases[$relation] ?? $relation;

            $rootRelation = str_contains($aliasOrRelation, '.')
                ? explode('.', $aliasOrRelation, 2)[0]
                : $aliasOrRelation;

            if (!in_array($rootRelation, $availableIncludes, true)) {
                $invalidIncludes[] = $relation;
            }
        }

        if (!empty($invalidIncludes)) {
            throw new InvalidArgumentException(
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

        $validFilters     = [];
        $filterableFields = $this->repository->filterableFields ?? [];
        $searchableFields = $this->repository->fieldSearchable  ?? [];

        foreach ($filters as $field => $value) {
            $baseField = explode('.', $field)[0];

            $whitelist = !empty($filterableFields) ? $filterableFields : $searchableFields;

            if (!array_key_exists($field, $whitelist) && !array_key_exists($baseField, $whitelist)) {
                continue;
            }

            $operator = $filterableFields[$field]
                ?? $filterableFields[$baseField]
                ?? $searchableFields[$field]
                ?? $searchableFields[$baseField]
                ?? '=';

            if (is_array($value)) {
                $parsedFilter = $this->parseComplexFilter($field, $value, $operator);

                if ($parsedFilter) {
                    $validFilters[] = $parsedFilter;
                }
            } else {
                $validFilters[] = [
                    'field'    => $field,
                    'operator' => $operator,
                    'value'    => $value,
                ];
            }
        }

        return $validFilters;
    }

    protected function parseSearch(Request $request): ?array
    {
        $term = $request->query('search');

        if ($term === null || $term === '') {
            return null;
        }

        $searchableFields = $this->repository->fieldSearchable ?? [];

        if (empty($searchableFields)) {
            return null;
        }

        $fieldsParam = $request->query('searchFields', '');

        $fields = [];

        if (!empty($fieldsParam)) {
            $requested = is_array($fieldsParam) ? $fieldsParam : explode(',', $fieldsParam);
            $requested = array_values(array_filter(array_map('trim', $requested)));

            foreach ($requested as $f) {
                $base = explode('.', $f)[0];

                if (array_key_exists($f, $searchableFields) || array_key_exists($base, $searchableFields)) {
                    $fields[$f] = $searchableFields[$f] ?? $searchableFields[$base] ?? 'like';
                }
            }
        }

        if (empty($fields)) {
            foreach ($searchableFields as $f => $op) {
                $fields[$f] = $op ?: 'like';
            }
        }

        $join = strtolower((string) $request->query('searchJoin', 'or'));
        $join = in_array($join, ['and', 'or'], true) ? $join : 'or';

        return [
            'term'   => $term,
            'fields' => $fields,
            'join'   => $join,
        ];
    }

    protected function parseComplexFilter(string $field, array $value, string $defaultOperator): ?array
    {
        if (isset($value[0])) {
            return [
                'field'    => $field,
                'operator' => 'in',
                'value'    => $value,
            ];
        }

        if (isset($value['gte']) || isset($value['lte'])) {
            return [
                'field'    => $field,
                'operator' => 'between',
                'value'    => [
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
        $sorts      = explode(',', $sortParam);
        $validSorts = [];

        foreach ($sorts as $sort) {
            $sort = trim($sort);

            if (empty($sort)) {
                continue;
            }

            $direction = 'asc';
            $field     = $sort;

            if (str_starts_with($sort, '-')) {
                $direction = 'desc';
                $field     = substr($sort, 1);
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
            throw new InvalidArgumentException(
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
            'page'     => (int) $request->query('page', 1),
        ];
    }

    protected function applyEagerLoading(Builder $query, array $includes): Builder
    {
        $relations = $includes['relations'] ?? $includes;

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query;
    }

    protected function applyWithCount(Builder $query, array $withCount): Builder
    {
        // Backward compatibility: if array is a flat list, treat as root counts
        $root   = $withCount['root']   ?? (array_values(array_filter($withCount, 'is_string')) ?: []);
        $nested = $withCount['nested'] ?? [];

        if (!empty($root)) {
            $query->withCount($root);
        }

        if (!empty($nested)) {
            foreach ($nested as $relation => $counts) {
                $query->with([
                    $relation => function ($q) use ($counts) {
                        $q->withCount($counts);
                    },
                ]);
            }
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
        $value    = $filter['value'];

        if (str_contains($field, '.')) {
            return $this->applyNestedFilter($query, $field, $operator, $value);
        }

        return match ($operator) {
            'in'      => $query->whereIn($field, is_array($value) ? $value : [$value]),
            'not_in'  => $query->whereNotIn($field, is_array($value) ? $value : [$value]),
            'between' => $query->whereBetween($field, $value),
            'like'    => $query->where($field, 'like', $value),
            '!=', '<>', '>', '>=', '<', '<=' => $query->where($field, $operator, $value),
            default => $query->where($field, $value),
        };
    }

    protected function applyNestedFilter(Builder $query, string $field, string $operator, mixed $value): Builder
    {
        $parts         = explode('.', $field, 2);
        $relation      = $parts[0];
        $relationField = $parts[1];

        return $query->whereHas($relation, function (Builder $q) use ($relationField, $operator, $value) {
            $qualifiedField = str_contains($relationField, '.')
                ? $relationField
                : $q->getModel()->qualifyColumn($relationField);

            match ($operator) {
                'in'      => $q->whereIn($qualifiedField, is_array($value) ? $value : [$value]),
                'not_in'  => $q->whereNotIn($qualifiedField, is_array($value) ? $value : [$value]),
                'between' => $q->whereBetween($qualifiedField, $value),
                'like'    => $q->where($qualifiedField, 'like', $value),
                '!=', '<>', '>', '>=', '<', '<=' => $q->where($qualifiedField, $operator, $value),
                default => $q->where($qualifiedField, $value),
            };
        });
    }

    protected function applySearchToQuery(Builder $query, ?array $search): Builder
    {
        if (!$search) {
            return $query;
        }

        $term   = $search['term'];
        $fields = $search['fields'];
        $join   = $search['join'];

        return $query->where(function (Builder $q) use ($fields, $term, $join) {
            $first = true;

            foreach ($fields as $field => $operator) {
                $value = $operator === 'like' ? ('%' . $term . '%') : $term;

                if (str_contains($field, '.')) {
                    [$relation, $relationField] = explode('.', $field, 2);

                    $clause = function (Builder $rel) use ($relationField, $operator, $value) {
                        $qualifiedField = str_contains($relationField, '.')
                            ? $relationField
                            : $rel->getModel()->qualifyColumn($relationField);

                        match ($operator) {
                            'in'      => $rel->whereIn($qualifiedField, is_array($value) ? $value : [$value]),
                            'not_in'  => $rel->whereNotIn($qualifiedField, is_array($value) ? $value : [$value]),
                            'between' => $rel->whereBetween($qualifiedField, $value),
                            'like'    => $rel->where($qualifiedField, 'like', $value),
                            '!=', '<>', '>', '>=', '<', '<=' => $rel->where($qualifiedField, $operator, $value),
                            default => $rel->where($qualifiedField, $value),
                        };
                    };

                    if ($first || $join === 'and') {
                        $q->whereHas($relation, $clause);
                    } else {
                        $q->orWhereHas($relation, $clause);
                    }
                } else {
                    if ($first || $join === 'and') {
                        $this->applyFilter($q, $field, [
                            'field'    => $field,
                            'operator' => $operator,
                            'value'    => $value,
                        ]);
                    } else {
                        $q->orWhere(function (Builder $qq) use ($field, $operator, $value) {
                            $this->applyFilter($qq, $field, [
                                'field'    => $field,
                                'operator' => $operator,
                                'value'    => $value,
                            ]);
                        });
                    }
                }

                $first = false;
            }
        });
    }

    protected function applySortToQuery(Builder $query, array $sorts): Builder
    {
        foreach ($sorts as $sort) {
            $query->orderBy($sort['field'], $sort['direction']);
        }

        return $query;
    }

    protected function buildPaginationMeta(LengthAwarePaginator|Paginator $paginator): array
    {
        $meta = [
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
            ],
            'links' => [
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
        ];

        if ($paginator instanceof LengthAwarePaginator) {
            $meta['pagination']['total']     = $paginator->total();
            $meta['pagination']['last_page'] = $paginator->lastPage();
            $meta['pagination']['from']      = $paginator->firstItem();
            $meta['pagination']['to']        = $paginator->lastItem();

            $meta['links']['first'] = $paginator->url(1);
            $meta['links']['last']  = $paginator->url($paginator->lastPage());

            return $meta;
        }

        $count = $paginator->count();
        $from  = $count ? (($paginator->currentPage() - 1) * $paginator->perPage()) + 1 : null;
        $to    = $count && $from !== null ? $from                                   + $count - 1 : null;

        $meta['pagination']['total']     = null;
        $meta['pagination']['last_page'] = null;
        $meta['pagination']['from']      = $from;
        $meta['pagination']['to']        = $to;

        $meta['links']['first'] = $paginator->url(1);
        $meta['links']['last']  = null;

        return $meta;
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
