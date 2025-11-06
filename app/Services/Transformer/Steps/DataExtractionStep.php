<?php

declare(strict_types=1);

namespace App\Services\Transformer\Steps;

use App\Contracts\Transformer\DataExtractorInterface;
use App\Contracts\Transformer\TransformationStepInterface;
use App\Services\Transformer\ModelTransformationContext;
use App\Services\Transformer\TransformationContext;

/**
 * Step for extracting data from models.
 */
readonly class DataExtractionStep implements TransformationStepInterface
{
    public function __construct(
        private DataExtractorInterface $extractor,
    ) {
    }

    /**
     * Extract data from the model and update the context.
     *
     * @param TransformationContext $context
     * @return TransformationContext
     */
    public function process(TransformationContext $context): TransformationContext
    {
        if (!$context instanceof ModelTransformationContext) {
            return $context;
        }

        // If data is already provided, skip extraction
        if (!empty($context->data)) {
            return $context;
        }

        // Extract data from model
        $extractedData = $this->extractor->extract($context->model);

        return $context->withData($extractedData);
    }
}
