<?php

declare(strict_types=1);

namespace App\Services\Transformer;

use App\Contracts\Transformer\VirtualFieldResolverInterface;
use App\Exceptions\Transformer\VirtualFieldNotFoundException;

/**
 * Resolver for virtual/computed fields using explicit callbacks.
 */
readonly class VirtualFieldResolver implements VirtualFieldResolverInterface
{
    /**
     * @param array<string, callable> $resolvers
     */
    public function __construct(
        private array $resolvers,
    ) {
    }

    /**
     * Resolve a virtual field value.
     *
     * @param string $fieldName
     * @param mixed $model
     * @return mixed
     * @throws VirtualFieldNotFoundException
     */
    public function resolve(string $fieldName, mixed $model): mixed
    {
        if (!isset($this->resolvers[$fieldName])) {
            throw new VirtualFieldNotFoundException($fieldName);
        }

        return ($this->resolvers[$fieldName])($model);
    }

    /**
     * Get available virtual fields.
     *
     * @return array
     */
    public function getAvailableFields(): array
    {
        return array_keys($this->resolvers);
    }
}
