<?php

declare(strict_types=1);

namespace App\Contracts\Entity;

use Illuminate\Database\Eloquent\Builder;

interface SortableEntity
{
    public function reorderItemsAfterRemoval(): int;

    public function shiftOrdersDown(int $newOrder): bool;

    public function shiftOrdersUp(int $newOrder): bool;

    public function baseSortQuery(): Builder;

    public function sortingColumnName(): string;

    public function determineNextSortOrderAtEnd(): int;

    public function getTotalItemCount(): int;
}
