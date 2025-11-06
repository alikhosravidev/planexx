<?php

declare(strict_types=1);

namespace App\Services\Transformer;

use App\Contracts\Transformer\FieldTransformerInterface;
use App\Exceptions\Transformer\InvalidTransformerException;
use Illuminate\Contracts\Container\Container;

/**
 * Registry for field transformers with validation.
 */
class FieldTransformerRegistry
{
    private array $transformers = [];

    public function __construct(
        private readonly Container $container,
    ) {
    }

    /**
     * Register a transformer for a field.
     *
     * @throws InvalidTransformerException
     */
    public function register(string $field, string $transformerClass): void
    {
        if (!class_exists($transformerClass)) {
            throw new InvalidTransformerException("Transformer class '{$transformerClass}' does not exist");
        }

        if (!is_subclass_of($transformerClass, FieldTransformerInterface::class)) {
            throw new InvalidTransformerException("Transformer class '{$transformerClass}' must implement FieldTransformerInterface");
        }

        $this->transformers[$field] = $transformerClass;
    }

    /**
     * Resolve a transformer instance for a field.
     */
    public function resolve(string $field): ?FieldTransformerInterface
    {
        $transformerClass = $this->transformers[$field] ?? null;

        if (!$transformerClass) {
            return null;
        }

        return $this->container->make($transformerClass);
    }

    /**
     * Check if a field has a registered transformer.
     */
    public function has(string $field): bool
    {
        return isset($this->transformers[$field]);
    }
}
