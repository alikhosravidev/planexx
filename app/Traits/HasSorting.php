<?php

declare(strict_types=1);

namespace App\Traits;

use App\Exceptions\SortOrderException;
use Illuminate\Database\Eloquent\Builder;

trait HasSorting
{
    public function sortingColumnName(): string
    {
        return 'sort';
    }

    public function baseSortQuery(): Builder
    {
        return $this->newQuery();
    }

    /**
     * @throws SortOrderException
     */
    public function reorderItemsAfterRemoval(): int
    {
        return $this->baseSortQuery()
            ->where(
                column: $this->sortingColumnName(),
                operator: '>',
                value: $this->getCurrentOrder()
            )
            ->decrement(column: $this->sortingColumnName());
    }

    public function shiftOrdersUp(int $newOrder): bool
    {
        $sortingColumn = $this->sortingColumnName();

        return (bool) $this->baseSortQuery()
            ->whereBetween(
                column: $sortingColumn,
                values: [$newOrder, $this->getCurrentOrder() - 1]
            )
            ->increment(column: $sortingColumn);
    }

    public function shiftOrdersDown(int $newOrder): bool
    {
        $sortingColumn = $this->sortingColumnName();

        return (bool) $this->baseSortQuery()
            ->whereBetween(
                column: $sortingColumn,
                values: [
                    $this->getCurrentOrder() + 1,
                    $newOrder,
                ]
            )
            ->decrement(column: $sortingColumn);
    }

    public function determineNextSortOrderAtEnd(): int
    {
        $lastRecord = $this->baseSortQuery()
            ->orderBy($this->sortingColumnName(), direction: 'desc')
            ->first();

        return $lastRecord && $lastRecord->exists ? $lastRecord->{$this->sortingColumnName()} + 1 : 1;
    }

    public function getTotalItemCount(): int
    {
        return $this->baseSortQuery()->count();
    }

    /**
     * @throws SortOrderException
     */
    public function getCurrentOrder(): int
    {
        $sortingColumn = $this->sortingColumnName();

        return $this->{$sortingColumn}
            ?? throw SortOrderException::currentOrderNotAvailable($this->getKey() ?? 0);
    }
}
