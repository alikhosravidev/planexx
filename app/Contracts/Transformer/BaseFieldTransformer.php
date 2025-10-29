<?php

declare(strict_types=1);

namespace App\Contracts\Transformer;

/**
 * Base class for field transformers.
 * Provides common functionality for field transformations.
 */
abstract class BaseFieldTransformer implements FieldTransformerInterface
{
    protected bool $isMany = false;

    /**
     * Set if this transformer is for a many operation.
     */
    public function setIsMany(bool $isMany): self
    {
        $this->isMany = $isMany;
        return $this;
    }

    /**
     * Transform for a single item.
     */
    public function transformOne(mixed $value): mixed
    {
        $this->isMany = false;
        return $this->transform($value);
    }

    /**
     * Transform for many items.
     */
    public function transformMany(mixed $value): mixed
    {
        $this->isMany = true;
        return $this->transform($value);
    }
}
