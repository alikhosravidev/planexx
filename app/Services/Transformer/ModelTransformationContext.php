<?php

declare(strict_types=1);

namespace App\Services\Transformer;

use App\Contracts\Entity\EntityInterface;

/**
 * Immutable DTO for model transformation pipeline context.
 */
readonly class ModelTransformationContext extends TransformationContext
{
    public EntityInterface $model;

    public function __construct(
        array           $data,
        EntityInterface $model,
        array           $metadata = [],
    ) {
        parent::__construct($data, $model, $metadata);
        $this->model = $model;
    }

    /**
     * Create a new context with updated data.
     *
     * @param array $data
     * @return self
     */
    public function withData(array $data): self
    {
        return new self($data, $this->model, $this->metadata);
    }

    /**
     * Create a new context with updated metadata.
     *
     * @param array $metadata
     * @return self
     */
    public function withMetadata(array $metadata): self
    {
        return new self($this->data, $this->model, $metadata);
    }
}
