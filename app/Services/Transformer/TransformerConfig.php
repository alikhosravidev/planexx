<?php

declare(strict_types=1);

namespace App\Services\Transformer;

/**
 * Immutable configuration for transformers.
 */
readonly class TransformerConfig
{
    public function __construct(
        public array $fieldTransformers,
        public array $blacklistedFields,
        public array $availableIncludes,
        public array $defaultIncludes,
        public bool $includeAccessors,
    ) {
    }

    /**
     * Create a default configuration with standard transformers.
     */
    public static function default(): self
    {
        return new self(
            fieldTransformers: config('transformer.field_transformers', []),
            blacklistedFields: config('transformer.blacklisted_fields', []),
            availableIncludes: config('transformer.available_includes', []),
            defaultIncludes: config('transformer.default_includes', []),
            includeAccessors: config('transformer.include_accessors', true),
        );
    }

    /**
     * Get the transformer class for a field.
     */
    public function getFieldTransformer(string $field): ?string
    {
        return $this->fieldTransformers[$field] ?? null;
    }

    /**
     * Check if a field is blacklisted.
     */
    public function isBlacklisted(string $field): bool
    {
        return in_array($field, $this->blacklistedFields, true);
    }

    /**
     * Merge this config with another config.
     */
    public function merge(self $other): self
    {
        return new self(
            fieldTransformers: array_merge($this->fieldTransformers, $other->fieldTransformers),
            blacklistedFields: array_merge($this->blacklistedFields, $other->blacklistedFields),
            availableIncludes: array_merge($this->availableIncludes, $other->availableIncludes),
            defaultIncludes: array_merge($this->defaultIncludes, $other->defaultIncludes),
            includeAccessors: $other->includeAccessors,
        );
    }
}
