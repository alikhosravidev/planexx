<?php

declare(strict_types=1);

namespace App\Contracts\Transformer;

use App\Contracts\Model\BaseModel;
use App\Contracts\Model\BaseModelContract;
use App\Services\Transformer\FieldTransformerRegistry;
use App\Services\Transformer\ModelTransformationContext;
use App\Services\Transformer\Steps\BlacklistFilterStep;
use App\Services\Transformer\Steps\DataExtractionStep;
use App\Services\Transformer\Steps\FieldTransformationStep;
use App\Services\Transformer\TransformationPipeline;
use App\Services\Transformer\TransformerConfig;
use App\Services\Transformer\VirtualFieldResolver;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Psr\Log\LoggerInterface;

abstract class BaseTransformer extends TransformerAbstract implements TransformerInterface
{
    protected array $fieldTransformers = [];

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
     * @param BaseModel $model
     * @return array
     */
    public function transformModel(BaseModel $model): array
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
     * @param BaseModel $data
     * @return array
     */
    public function transform(BaseModel $data): array
    {
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
        $this->manager->parseIncludes($includes);

        return $this;
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
        return new \App\Services\Transformer\Steps\VirtualFieldResolutionStep(
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
        if ($model instanceof BaseModelContract) {
            return $this->transform($model);
        }

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
}
