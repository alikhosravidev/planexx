<?php

declare(strict_types=1);

namespace Modules\Product\Services\CustomList\Actions;

use Modules\Product\Collections\CustomListValueDTOCollection;
use Modules\Product\Entities\CustomListItem;

/**
 * Persists a collection of value DTOs as CustomListValue
 * records associated with a given item using bulk insert.
 */
final class SaveItemValuesAction
{
    public function execute(CustomListItem $item, CustomListValueDTOCollection $values): void
    {
        if ($values->isEmpty()) {
            return;
        }

        $data = $values->map(function ($valueDto) use ($item) {
            return array_merge(
                $valueDto->toArray(),
                ['item_id' => $item->id]
            );
        })->toArray();

        $item->values()->insert($data);
    }
}
