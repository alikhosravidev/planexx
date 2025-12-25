<?php

declare(strict_types=1);

namespace App\Contracts\Transformer;

use App\Contracts\Entity\EntityInterface;
use App\Exceptions\Transformer\TransformerException;
use App\Services\Transformer\FieldTransformerRegistry;
use App\Services\Transformer\ModelTransformationContext;
use App\Services\Transformer\Steps\BlacklistFilterStep;
use App\Services\Transformer\Steps\DataExtractionStep;
use App\Services\Transformer\Steps\FieldTransformationStep;
use App\Services\Transformer\Steps\VirtualFieldResolutionStep;
use App\Services\Transformer\TransformationPipeline;
use App\Services\Transformer\TransformerConfig;
use App\Services\Transformer\VirtualFieldResolver;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection as FractalCollection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Psr\Log\LoggerInterface;

abstract class BaseTransformer extends TransformerAbstract implements TransformerInterface
{
    protected array $fieldTransformers = [];
    protected array $includeAliases    = [];

    public function __construct(
        protected readonly TransformerConfig $config,
        protected readonly FieldTransformerRegistry $registry,
        protected readonly DataExtractorInterface $extractor,
        protected readonly Manager $manager,
        protected readonly LoggerInterface $logger,
    ) {
        $this->registerFieldTransformers();
    }

    protected function registerFieldTransformers(): void
    {
        foreach ($this->fieldTransformers as $field => $transformerClass) {
            $this->registry->register($field, $transformerClass);
        }
    }

    /**
     * Transform a single model to array format.
     *
     * @param EntityInterface $model
     * @return array
     */
    public function transformModel(EntityInterface $model): array
    {
        return $this->transform($model);
    }

    /**
     * Transform an array to array format.
     *
     * @param array $data
     * @return array
     */
    public function transformArray(array $data): array
    {
        return $this->transform($data);
    }

    /**
     * Transform a collection of models to array format.
     *
     * @param Collection $models
     * @return array
     */
    public function transformCollection(Collection $models): array
    {
        return $this->transformMany($models->toArray(), null);
    }

    /**
     * Transform method required by Fractal.
     *
     * @param EntityInterface $data
     * @return array
     */
    public function transform(?EntityInterface $data): array
    {
        if ($data === null) {
            return [];
        }

        $pipeline = $this->buildPipeline();
        $context  = new ModelTransformationContext([], $data);
        $result   = $pipeline->process($context);

        return $result->data;
    }

    /**
     * Set includes for the Fractal manager.
     *
     * @param array $includes
     * @return static
     */
    public function setIncludes(array $includes): static
    {
        if (isset($includes['relations'])) {
            $this->includeAliases = $includes['aliases'] ?? [];
            $includesForFractal   = [];

            foreach ($includes['relations'] as $relation) {
                $includesForFractal[] = $this->includeAliases[$relation] ?? $relation;
            }

            $this->manager->parseIncludes($includesForFractal);

            return $this;
        }

        $this->manager->parseIncludes($includes);

        return $this;
    }

    public function getIncludeAliases(): array
    {
        return $this->includeAliases;
    }

    /**
     * Set excludes for the Fractal manager.
     *
     * @param array $excludes
     * @return static
     */
    public function setExcludes(array $excludes): static
    {
        $this->manager->parseExcludes($excludes);

        return $this;
    }

    /**
     * Configure the transformer with request parameters.
     *
     * @param Request $request
     * @return static
     */
    public function withRequest(Request $request): static
    {
        $includes = $request->get('includes');

        if ($includes) {
            $this->setIncludes(is_string($includes) ? explode(',', $includes) : $includes);
        }

        $excludes = $request->get('excludes');

        if ($excludes) {
            $this->setExcludes(is_string($excludes) ? explode(',', $excludes) : $excludes);
        }

        return $this;
    }

    /**
     * Build the transformation pipeline with default steps.
     *
     * @return TransformationPipeline
     */
    protected function buildPipeline(): TransformationPipeline
    {
        return new TransformationPipeline([
            $this->createDataExtractionStep(),
            $this->createBlacklistFilterStep(),
            $this->createFieldTransformationStep(),
            $this->createVirtualFieldResolutionStep(),
        ]);
    }

    /**
     * Create the data extraction step.
     *
     * @return TransformationStepInterface
     */
    protected function createDataExtractionStep(): TransformationStepInterface
    {
        return new DataExtractionStep($this->extractor);
    }

    /**
     * Create the blacklist filter step.
     *
     * @return TransformationStepInterface
     */
    protected function createBlacklistFilterStep(): TransformationStepInterface
    {
        return new BlacklistFilterStep($this->config);
    }

    /**
     * Create the field transformation step.
     *
     * @return TransformationStepInterface
     */
    protected function createFieldTransformationStep(): TransformationStepInterface
    {
        return new FieldTransformationStep($this->registry);
    }

    /**
     * Create the virtual field resolution step.
     *
     * @return TransformationStepInterface
     */
    protected function createVirtualFieldResolutionStep(): TransformationStepInterface
    {
        return new VirtualFieldResolutionStep(
            $this->createVirtualFieldResolver(),
            $this->logger
        );
    }

    /**
     * Create the virtual field resolver.
     *
     * @return VirtualFieldResolverInterface
     */
    protected function createVirtualFieldResolver(): VirtualFieldResolverInterface
    {
        return new VirtualFieldResolver($this->getVirtualFieldResolvers());
    }

    /**
     * Get virtual field resolvers for this transformer.
     * Override in child classes to provide virtual fields.
     *
     * @return array<string, callable>
     */
    protected function getVirtualFieldResolvers(): array
    {
        return [];
    }

    /**
     * Transform a single item.
     */
    public function transformOne($model, ?string $resourceKey = null): array
    {
        $resource = $this->item($model, $this, $resourceKey);

        return $this->manager->createData($resource)->toArray();
    }

    /**
     * Transform a collection.
     */
    public function transformMany($model, ?string $resourceKey = null): array
    {
        $resource = $this->collection($model, $this, $resourceKey);

        return $this->manager->createData($resource)->toArray();
    }

    /**
     * Get available includes.
     */
    public function getAvailableIncludes(): array
    {
        if (!empty($this->availableIncludes)) {
            return $this->availableIncludes;
        }

        return $this->config->availableIncludes;
    }

    /**
     * Get default includes.
     */
    public function getDefaultIncludes(): array
    {
        if (!empty($this->defaultIncludes)) {
            return $this->defaultIncludes;
        }

        return $this->config->defaultIncludes;
    }

    public function setDefaultIncludes(array $includes): static
    {
        $this->defaultIncludes = $includes;

        return $this;
    }

    /**
     * @param  \App\Contracts\Transformer\TransformerInterface|string  $transformer
     * @return bool
     */
    private function isValidTransformer(TransformerInterface|string $transformer): bool
    {
        if ($transformer instanceof TransformerInterface) {
            return true;
        }

        return is_subclass_of($transformer, TransformerInterface::class);
    }

    /**
     * Include a relationship with optional transformer.
     */
    protected function includeRelation(
        string $name,
        callable $callback,
        ?TransformerInterface $transformer = null
    ): callable {
        return function ($model) use ($name, $callback, $transformer) {
            $relation = $callback($model);

            if ($transformer) {
                return $transformer->transform($relation);
            }

            return $relation;
        };
    }

    /**
     * Safely include a single model relation as an item.
     */
    protected function itemRelation(
        EntityInterface $model,
        string $relationName,
        TransformerInterface|string $transformer,
        ?string $foreignKey = null,
        ?string $resourceKey = null,
    ): ?Item {
        if ($foreignKey !== null && empty($model->{$foreignKey})) {
            return null;
        }
        $result = $this->prepareRelationInclude($model, $relationName, $transformer);

        if ($result === null) {
            return null;
        }

        [$related, $transformerInstance] = $result;

        return $this->item($related, $transformerInstance, $resourceKey);
    }

    /**
     * Safely include a to-many relation as a collection.
     */
    protected function collectionRelation(
        EntityInterface $model,
        string $relationName,
        TransformerInterface|string $transformer,
        ?string $resourceKey = null,
    ): ?FractalCollection {
        $result = $this->prepareRelationInclude($model, $relationName, $transformer);

        if ($result === null) {
            return null;
        }

        [$related, $transformerInstance] = $result;

        return $this->collection($related, $transformerInstance, $resourceKey);
    }

    private function canIncludeRelation(EntityInterface $model, string $relationName): bool
    {
        if (!method_exists($model, $relationName)) {
            return false;
        }

        if (method_exists($model, 'relationLoaded') && !$model->relationLoaded($relationName)) {
            return false;
        }

        return true;
    }

    private function prepareRelationInclude(
        EntityInterface $model,
        string $relationName,
        TransformerInterface|string $transformer,
    ): ?array {
        if (! $this->isValidTransformer($transformer)) {
            $transformerClass = is_string($transformer) ? $transformer : get_class($transformer);

            throw new TransformerException("Transformer class '{$transformerClass}' is invalid.");
        }

        if (! $this->canIncludeRelation($model, $relationName)) {
            return null;
        }

        $related = $model->{$relationName};

        if ($related === null) {
            return null;
        }

        $transformerInstance = is_string($transformer) ? resolve($transformer) : $transformer;

        return [$related, $transformerInstance];
    }
}
