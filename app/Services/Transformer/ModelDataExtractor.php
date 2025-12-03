<?php

declare(strict_types=1);

namespace App\Services\Transformer;

use App\Contracts\Entity\EntityInterface;
use App\Contracts\Transformer\DataExtractorInterface;

/**
 * Extracts data from BaseModel instances.
 */
class ModelDataExtractor implements DataExtractorInterface
{
    public function __construct(
        private bool $includeAccessors = true,
    ) {
    }

    /**
     * Extract data from a BaseModel.
     */
    public function extract(EntityInterface $model): array
    {
        $data = array_merge(
            $model->attributesToArray(),
            $model->relationsToArray()
        );

        $appends = $model->getAppends();

        if ($this->includeAccessors) {
            return $this->includeAccessors($appends, $model, $data);
        }

        foreach ($appends as $accessor) {
            unset($data[$accessor]);
        }

        return $data;
    }

    /**
     * Set whether to include accessors.
     */
    public function setIncludeAccessors(bool $include): void
    {
        $this->includeAccessors = $include;
    }

    private function includeAccessors(array $appends, EntityInterface $model, array $data): array
    {
        foreach ($appends as $accessor) {
            if (isset($model->$accessor)) {
                $data[$accessor] = $model->$accessor;
            }
        }

        return $data;
    }
}
