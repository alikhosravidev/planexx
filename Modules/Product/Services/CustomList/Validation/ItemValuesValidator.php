<?php

declare(strict_types=1);

namespace Modules\Product\Services\CustomList\Validation;

use Illuminate\Database\Eloquent\Collection;
use Modules\Product\Collections\CustomListValueDTOCollection;
use Modules\Product\DTOs\CustomListValueDTO;
use Modules\Product\Enums\AttributeDataTypeEnum;
use Modules\Product\Exceptions\CustomListException;

/**
 * Validates item values against the list's attribute schema:
 * checks for missing required values and data type mismatches.
 */
final class ItemValuesValidator
{
    /**
     * @throws CustomListException
     */
    public function validate(Collection $attributes, CustomListValueDTOCollection $values): void
    {
        $requiredAttributes = [];
        $attributeTypeMap   = [];

        foreach ($attributes as $attribute) {
            $attributeTypeMap[$attribute->id] = $attribute->data_type;

            if ($attribute->is_required) {
                $requiredAttributes[$attribute->id] = $attribute->data_type;
            }
        }

        $this->validateRequiredValues($requiredAttributes, $values, $attributes);
        $this->validateDataTypes($values, $attributeTypeMap, $attributes);
    }

    private function validateRequiredValues(
        array $requiredAttributes,
        CustomListValueDTOCollection $values,
        mixed $attributes,
    ): void {
        $providedIds = $values->map(fn (CustomListValueDTO $v) => $v->attributeId)->toArray();
        $missingIds  = array_diff(array_keys($requiredAttributes), $providedIds);

        if (empty($missingIds)) {
            return;
        }

        $missingLabels = $attributes
            ->whereIn('id', $missingIds)
            ->pluck('label')
            ->implode(', ');

        throw CustomListException::missingRequiredValues($missingLabels);
    }

    private function validateDataTypes(
        CustomListValueDTOCollection $values,
        array $attributeTypeMap,
        mixed $attributes,
    ): void {
        foreach ($values as $valueDto) {
            $dataType = $attributeTypeMap[$valueDto->attributeId] ?? null;

            if ($dataType === null) {
                throw CustomListException::invalidAttributeId($valueDto->attributeId);
            }

            if (! $this->isValueValidForDataType($valueDto, $dataType)) {
                $attribute = $attributes->firstWhere('id', $valueDto->attributeId);

                throw CustomListException::valueTypeMismatch($attribute->label, $dataType->label());
            }
        }
    }

    private function isValueValidForDataType(CustomListValueDTO $valueDto, AttributeDataTypeEnum $dataType): bool
    {
        return match ($dataType) {
            AttributeDataTypeEnum::Text,
            AttributeDataTypeEnum::Select  => $valueDto->valueText    !== null,
            AttributeDataTypeEnum::Number  => $valueDto->valueNumber  !== null,
            AttributeDataTypeEnum::Date    => $valueDto->valueDate    !== null,
            AttributeDataTypeEnum::Boolean => $valueDto->valueBoolean !== null,
        };
    }
}
