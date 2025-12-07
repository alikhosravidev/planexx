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
        $filteredData = $this->filterRecursively($context->data);

        return $context->withData($filteredData);
    }

    private function filterRecursively(array $data): array
    {
        $filteredData = array_filter(
            $data,
            fn ($field) => !$this->config->isBlacklisted($field),
            ARRAY_FILTER_USE_KEY
        );

        foreach ($filteredData as $field => $value) {
            if ($this->isNestedCollection($value)) {
                $filteredData[$field] = array_map(
                    fn (array $item) => $this->filterRecursively($item),
                    $value
                );

                continue;
            }

            if ($this->isNestedAssociativeArray($value)) {
                $filteredData[$field] = $this->filterRecursively($value);
            }
        }

        return $filteredData;
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
