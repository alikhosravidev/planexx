<?php

declare(strict_types=1);

namespace App\Observers;

use App\Contracts\Model\BaseModelContract;
use App\Contracts\Sorting\SortableEntity;

class GlobalRecordsOrderingObserver
{
    public function deleted(BaseModelContract $model): void
    {
        if ($model instanceof SortableEntity) {
            $model->reorderItemsAfterRemoval();
        }
    }

    public function creating(BaseModelContract $model): void
    {
        if ($model instanceof SortableEntity) {
            $lastPosition = $model->determineNextSortOrderAtEnd();

            if (empty($model->{$model->sortingColumnName()})) {
                $model->{$model->sortingColumnName()} = $lastPosition;
            }
        }
    }
}
