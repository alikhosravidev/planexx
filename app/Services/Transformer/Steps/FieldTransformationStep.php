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
        $transformedData = $context->data;

        foreach ($transformedData as $field => $value) {
            if (null === $value) {
                continue;
            }

            if ($context->originalModel instanceof EntityInterface) {
                $value = $context->originalModel->{$field};
            }

            $transformer = $this->registry->resolve($field);

            if ($transformer) {
                $transformedData[$field] = $transformer->transform($value);
            }
        }

        return $context->withData($transformedData);
    }
}
