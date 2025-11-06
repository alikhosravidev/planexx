<?php

declare(strict_types=1);

namespace App\Services\Transformer\Steps;

use App\Contracts\Transformer\TransformationStepInterface;
use App\Services\Transformer\TransformationContext;
use App\Services\Transformer\TransformerConfig;

/**
 * Step for filtering out blacklisted fields.
 */
class BlacklistFilterStep implements TransformationStepInterface
{
    public function __construct(
        private readonly TransformerConfig $config,
    ) {
    }

    /**
     * Remove blacklisted fields from the data.
     *
     * @param TransformationContext $context
     * @return TransformationContext
     */
    public function process(TransformationContext $context): TransformationContext
    {
        $filteredData = array_filter(
            $context->data,
            fn ($field) => !$this->config->isBlacklisted($field),
            ARRAY_FILTER_USE_KEY
        );

        return $context->withData($filteredData);
    }
}
