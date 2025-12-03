<?php

declare(strict_types=1);

namespace App\Observers;

use App\Contracts\Entity\EntityInterface;
use App\Contracts\Entity\SortableEntity;

class GlobalRecordsOrderingObserver
{
    public function deleted(EntityInterface $model): void
    {
        if ($model instanceof SortableEntity) {
            $model->reorderItemsAfterRemoval();
        }
    }

    public function creating(EntityInterface $model): void
    {
        if ($model instanceof SortableEntity) {
            $lastPosition = $model->determineNextSortOrderAtEnd();

            if (empty($model->{$model->sortingColumnName()})) {
                $model->{$model->sortingColumnName()} = $lastPosition;
            }
        }
    }
}
