<?php

declare(strict_types=1);

namespace App\Contracts\Transformer;

use App\Services\Transformer\TransformationContext;

/**
 * Interface for transformation pipeline steps.
 */
interface TransformationStepInterface
{
    /**
     * Process the transformation context and return the updated context.
     *
     * @param TransformationContext $context
     * @return TransformationContext
     */
    public function process(TransformationContext $context): TransformationContext;
}
