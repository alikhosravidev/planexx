<?php

declare(strict_types=1);

namespace App\Services\Transformer;

use App\Contracts\Transformer\TransformationStepInterface;

/**
 * Pipeline for executing transformation steps in sequence.
 */
readonly class TransformationPipeline
{
    /**
     * @param TransformationStepInterface[] $steps
     */
    public function __construct(
        private array $steps,
    ) {
    }

    /**
     * Process the model through all transformation steps.
     *
     * @param ModelTransformationContext $context
     * @return ModelTransformationContext
     */
    public function process(ModelTransformationContext $context): ModelTransformationContext
    {
        $currentContext = $context;

        foreach ($this->steps as $step) {
            $currentContext = $step->process($currentContext);
        }

        return $currentContext;
    }
}
