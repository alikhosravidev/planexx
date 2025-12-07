<?php

declare(strict_types=1);

namespace App\Services\Transformer\Steps;

use App\Contracts\Entity\EntityInterface;
use App\Contracts\Transformer\TransformationStepInterface;
use App\Services\Transformer\FieldTransformerRegistry;
use App\Services\Transformer\TransformationContext;

/**
 * Step for transforming individual fields using registered transformers.
 */
readonly class FieldTransformationStep implements TransformationStepInterface
{
    public function __construct(
        private FieldTransformerRegistry $registry,
    ) {
    }

    /**
     * Apply field transformers to the data.
     *
     * @param TransformationContext $context
     * @return TransformationContext
     */
    public function process(TransformationContext $context): TransformationContext
    {
        $transformedData = $this->transformRecursively(
            $context->data,
            $context->originalModel instanceof EntityInterface ? $context->originalModel : null
        );

        return $context->withData($transformedData);
    }

    private function transformRecursively(array $data, ?EntityInterface $model = null): array
    {
        $transformedData = $data;

        foreach ($transformedData as $field => $value) {
            if (null === $value) {
                continue;
            }

            if ($this->isNestedCollection($value)) {
                $transformedData[$field] = array_map(
                    fn (array $item) => $this->transformRecursively($item),
                    $value
                );

                continue;
            }

            $transformer = $this->registry->resolve($field);

            if ($transformer) {
                $originalValue           = $model?->{$field} ?? $value;
                $transformedData[$field] = $transformer->transform($originalValue);

                continue;
            }

            if ($this->isNestedAssociativeArray($value)) {
                $transformedData[$field] = $this->transformRecursively($value);
            }
        }

        return $transformedData;
    }

    private function isNestedCollection(mixed $value): bool
    {
        if (!is_array($value) || empty($value)) {
            return false;
        }

        return array_is_list($value) && is_array($value[0]);
    }

    private function isNestedAssociativeArray(mixed $value): bool
    {
        if (!is_array($value) || empty($value)) {
            return false;
        }

        return !array_is_list($value);
    }
}
