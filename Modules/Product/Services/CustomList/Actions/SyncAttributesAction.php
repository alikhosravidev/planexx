<?php

declare(strict_types=1);

namespace Modules\Product\Services\CustomList\Actions;

use Modules\Product\Collections\CustomListAttributeDTOCollection;
use Modules\Product\Entities\CustomList;

/**
 * Replaces all existing attributes on a CustomList with
 * those from the provided DTO collection using bulk insert.
 */
final class SyncAttributesAction
{
    public function execute(CustomList $list, CustomListAttributeDTOCollection $attributes): void
    {
        $list->attributes()->delete();

        if ($attributes->isEmpty()) {
            return;
        }

        $data = $attributes->map(function ($attrDto, $index) use ($list) {
            return array_merge(
                $attrDto->toArray(),
                [
                    'list_id'    => $list->id,
                    'sort_order' => $index,
                ]
            );
        })->toArray();

        $list->attributes()->insert($data);
    }
}
