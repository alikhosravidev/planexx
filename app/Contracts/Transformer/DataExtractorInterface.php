<?php

declare(strict_types=1);

namespace App\Contracts\Transformer;

use App\Contracts\Model\BaseModel;

/**
 * Interface for extracting data from models.
 */
interface DataExtractorInterface
{
    /**
     * Extract data from a model.
     *
     * @param BaseModel $model
     * @return array
     */
    public function extract(BaseModel $model): array;

    /**
     * Set whether to include accessors.
     *
     * @param bool $include
     * @return void
     */
    public function setIncludeAccessors(bool $include): void;
}
