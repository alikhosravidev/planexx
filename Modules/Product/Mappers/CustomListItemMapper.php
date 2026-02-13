<?php

declare(strict_types=1);

namespace Modules\Product\Mappers;

use App\Utilities\OrNull;
use Illuminate\Http\Request;
use Modules\Product\Collections\CustomListValueDTOCollection;
use Modules\Product\DTOs\CustomListItemDTO;
use Modules\Product\DTOs\CustomListValueDTO;
use Modules\Product\Entities\CustomListItem;
use Modules\Product\ValueObjects\CustomListId;

class CustomListItemMapper
{
    public function fromRequest(Request $request, int $listId): CustomListItemDTO
    {
        return new CustomListItemDTO(
            listId: new CustomListId($listId),
            referenceCode: OrNull::stringOrNull($request->input('reference_code')),
            values: $this->mapValues($request->input('values', [])),
        );
    }

    public function fromRequestForUpdate(Request $request, CustomListItem $item): CustomListItemDTO
    {
        return new CustomListItemDTO(
            listId: new CustomListId($item->list_id),
            referenceCode: $request->has('reference_code')
                ? OrNull::stringOrNull($request->input('reference_code'))
                : $item->reference_code,
            values: $request->has('values')
                ? $this->mapValues($request->input('values', []))
                : new CustomListValueDTOCollection(),
        );
    }

    /**
     * @param  array<int, array<string, mixed>>  $rawValues
     */
    private function mapValues(array $rawValues): CustomListValueDTOCollection
    {
        $dtos = array_map(
            fn (array $val) => new CustomListValueDTO(
                attributeId: (int) $val['attribute_id'],
                valueText: OrNull::stringOrNull($val['value_text'] ?? null),
                valueNumber: OrNull::floatOrNull($val['value_number'] ?? null),
                valueDate: isset($val['value_date'])
                    ? new \DateTimeImmutable($val['value_date'])
                    : null,
                valueBoolean: isset($val['value_boolean'])
                    ? (bool) $val['value_boolean']
                    : null,
            ),
            array_values($rawValues),
        );

        return new CustomListValueDTOCollection($dtos);
    }
}
