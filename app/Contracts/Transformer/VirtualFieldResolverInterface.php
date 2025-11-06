<?php

declare(strict_types=1);

namespace App\Contracts\Transformer;

/**
 * Interface for resolving virtual/computed fields.
 */
interface VirtualFieldResolverInterface
{
    /**
     * Resolve a virtual field value for the given model.
     *
     * @param string $fieldName
     * @param mixed $model
     * @return mixed
     */
    public function resolve(string $fieldName, mixed $model): mixed;

    /**
     * Get the list of available virtual fields.
     *
     * @return array
     */
    public function getAvailableFields(): array;
}
