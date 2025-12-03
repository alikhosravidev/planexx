<?php

declare(strict_types=1);

namespace App\Services\Transformer;

use App\Contracts\Entity\EntityInterface;

/**
 * Immutable DTO for transformation pipeline context.
 */
readonly class TransformationContext
{
    public function __construct(
        public array                 $data,
        public EntityInterface|array $originalModel,
        public array                 $metadata = [],
    ) {
    }

    /**
     * Create a new context with updated data.
     *
     * @param array $data
     * @return self
     */
    public function withData(array $data): self
    {
        return new self($data, $this->originalModel, $this->metadata);
    }

    /**
     * Create a new context with updated metadata.
     *
     * @param array $metadata
     * @return self
     */
    public function withMetadata(array $metadata): self
    {
        return new self($this->data, $this->originalModel, $metadata);
    }
}
