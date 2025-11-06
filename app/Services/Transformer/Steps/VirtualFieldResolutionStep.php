<?php

declare(strict_types=1);

namespace App\Services\Transformer\Steps;

use App\Contracts\Transformer\TransformationStepInterface;
use App\Contracts\Transformer\VirtualFieldResolverInterface;
use App\Services\Transformer\ModelTransformationContext;
use App\Services\Transformer\TransformationContext;
use Psr\Log\LoggerInterface;

/**
 * Step for resolving virtual/computed fields.
 */
readonly class VirtualFieldResolutionStep implements TransformationStepInterface
{
    public function __construct(
        private VirtualFieldResolverInterface $resolver,
        private LoggerInterface               $logger,
    ) {
    }

    /**
     * Add virtual fields to the data.
     *
     * @param TransformationContext $context
     * @return TransformationContext
     */
    public function process(TransformationContext $context): TransformationContext
    {
        if (!$context instanceof ModelTransformationContext) {
            return $context;
        }

        $data = $context->data;

        foreach ($this->resolver->getAvailableFields() as $fieldName) {
            try {
                $data[$fieldName] = $this->resolver->resolve($fieldName, $context->model);
            } catch (\Throwable $e) {
                $this->logger->warning("Failed to resolve virtual field: {$fieldName}", [
                    'exception' => $e->getMessage(),
                    'field'     => $fieldName,
                ]);
                continue;
            }
        }

        return $context->withData($data);
    }
}
