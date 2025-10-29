<?php

declare(strict_types=1);

namespace App\Contracts\Transformer;

/**
 * Interface for field transformers.
 * Ensures all field transformers implement a consistent interface.
 */
interface FieldTransformerInterface
{
    /**
     * Transform a single field value.
     *
     * @param mixed $value
     * @return mixed
     */
    public function transform(mixed $value): mixed;
}
