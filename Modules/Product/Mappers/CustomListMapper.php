<?php

declare(strict_types=1);

namespace Modules\Product\Mappers;

use App\Utilities\OrNull;
use Illuminate\Http\Request;
use Modules\Product\Collections\CustomListAttributeDTOCollection;
use Modules\Product\DTOs\CustomListAttributeDTO;
use Modules\Product\DTOs\CustomListDTO;
use Modules\Product\Entities\CustomList;
use Modules\Product\Enums\AttributeDataTypeEnum;

class CustomListMapper
{
    public function fromRequest(Request $request): CustomListDTO
    {
        return new CustomListDTO(
            title: $request->input('title'),
            slug: OrNull::stringOrNull($request->input('slug')),
            description: OrNull::stringOrNull($request->input('description')),
            iconClass: $request->input('icon_class', 'fa-solid fa-clipboard-list'),
            color: $request->input('color', 'blue'),
            isActive: $request->boolean('is_active', true),
            attributes: $this->mapAttributes($request->input('attributes', [])),
        );
    }

    public function fromRequestForUpdate(Request $request, CustomList $list): CustomListDTO
    {
        return new CustomListDTO(
            title: $request->input('title', $list->title),
            slug: OrNull::stringOrNull($request->input('slug')) ?? $list->slug,
            description: $request->has('description')
                ? OrNull::stringOrNull($request->input('description'))
                : $list->description,
            iconClass: $request->input('icon_class', $list->icon_class),
            color: $request->input('color', $list->color),
            isActive: $request->has('is_active')
                ? $request->boolean('is_active')
                : $list->is_active,
            attributes: $request->has('attributes')
                ? $this->mapAttributes($request->input('attributes', []))
                : new CustomListAttributeDTOCollection(),
        );
    }

    /**
     * @param  array<int, array<string, mixed>>  $rawAttributes
     */
    private function mapAttributes(array $rawAttributes): CustomListAttributeDTOCollection
    {
        $dtos = array_map(
            fn (array $attr, int $index) => new CustomListAttributeDTO(
                label: $attr['label'],
                keyName: $attr['key_name'],
                dataType: isset($attr['data_type'])
                    ? AttributeDataTypeEnum::from((int) $attr['data_type'])
                    : AttributeDataTypeEnum::Text,
                isRequired: (bool) ($attr['is_required'] ?? false),
                sortOrder: (int) ($attr['sort_order'] ?? $index),
            ),
            array_values($rawAttributes),
            array_keys(array_values($rawAttributes)),
        );

        return new CustomListAttributeDTOCollection($dtos);
    }
}
