<?php

declare(strict_types=1);

namespace App\Contracts\Transformer;

use App\Contracts\Model\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;

abstract class BaseTransformer extends TransformerAbstract
{
    protected array $blackListFields   = [];
    protected array $fieldTransformers = [];
    protected array $availableIncludes = [];
    protected array $defaultIncludes   = [];
    protected bool $includeAccessors   = true;

    private Manager $manager;

    public function __construct(
        protected Request $request
    ) {
        $this->manager = new Manager();

        // Parse includes and excludes from request
        $this->manager->parseIncludes(
            $request->get('includes') ?? $this->defaultIncludes
        );
        $this->manager->parseExcludes(
            $request->get('excludes') ?? []
        );
    }

    /**
     * Transform the model to array.
     * By default, includes all fields except blacklisted ones.
     * Applies field-specific transformers.
     *
     * @param Model|array $model
     * @return array|null
     */
    public function transform($model): ?array
    {
        $data = $this->extractData($model);
        $data = $this->applyBlacklist($data);
        $data = $this->transformFields($model, $data);

        return $data;
    }

    /**
     * Extract data from model (attributes + relations + accessors if enabled).
     */
    protected function extractData($model): array
    {
        if (!$model instanceof BaseModel) {
            return is_array($model) ? $model : [$model];
        }

        $data = array_merge(
            $model->attributesToArray(),
            $model->relationsToArray()
        );

        // Add accessors if enabled
        if ($this->includeAccessors) {
            foreach ($model->getAppends() as $accessor) {
                if (isset($model->$accessor)) {
                    $data[$accessor] = $model->$accessor;
                }
            }
        }

        return $data;
    }

    /**
     * Apply blacklist to remove unwanted fields.
     */
    protected function applyBlacklist(array $data): array
    {
        return array_diff_key($data, array_flip($this->blackListFields));
    }

    /**
     * Transform individual fields using field transformers.
     */
    protected function transformFields($model, array $data): array
    {
        foreach ($data as $field => $value) {
            if (isset($this->fieldTransformers[$field])) {
                $transformerClass = $this->fieldTransformers[$field];
                $transformer      = new $transformerClass();
                $data[$field]     = $transformer->transformOne($value);
            }
        }

        return $data;
    }

    /**
     * Transform a single item.
     */
    public function transformOne($model, ?string $resourceKey = null): mixed
    {
        $resource = $this->item($model, $this, $resourceKey);

        return $this->manager->createData($resource)->toArray();
    }

    /**
     * Transform a collection.
     */
    public function transformMany($model, ?string $resourceKey = null): mixed
    {
        $resource = $this->collection($model, $this, $resourceKey);

        return $this->manager->createData($resource)->toArray();
    }

    /**
     * Get available includes.
     */
    public function getAvailableIncludes(): array
    {
        return $this->availableIncludes;
    }

    /**
     * Get default includes.
     */
    public function getDefaultIncludes(): array
    {
        return $this->defaultIncludes;
    }

    /**
     * Include a relationship with optional transformer.
     */
    protected function includeRelation(string $name, callable $callback, ?TransformerAbstract $transformer = null): callable
    {
        return function ($model) use ($name, $callback, $transformer) {
            $relation = $callback($model);

            if ($transformer) {
                return $transformer->transform($relation);
            }

            return $relation;
        };
    }
}
