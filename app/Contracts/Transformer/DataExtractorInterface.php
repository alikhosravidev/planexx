<?php

declare(strict_types=1);

namespace App\Contracts\Transformer;

use App\Contracts\Entity\BaseEntity;

/**
 * Interface for extracting data from models.
 */
interface DataExtractorInterface
{
    /**
     * Extract data from a model.
     *
     * @param BaseEntity $model
     * @return array
     */
    public function extract(BaseEntity $model): array;

    /**
     * Set whether to include accessors.
     *
     * @param bool $include
     * @return void
     */
    public function setIncludeAccessors(bool $include): void;
}
