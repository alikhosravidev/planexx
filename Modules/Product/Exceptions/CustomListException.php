<?php

declare(strict_types=1);

namespace Modules\Product\Exceptions;

use App\Exceptions\BusinessException;

final class CustomListException extends BusinessException
{
    public static function duplicateAttributeKeys(array $duplicates): self
    {
        return new self(trans('Product::errors.duplicate_attribute_keys', ['duplicates' => implode(', ', $duplicates)]));
    }

    public static function missingRequiredValues(string $labels): self
    {
        return new self(trans('Product::errors.missing_required_values', ['labels' => $labels]));
    }

    public static function invalidAttributeId(int $attributeId): self
    {
        return new self(trans('Product::errors.invalid_attribute_id', ['attributeId' => $attributeId]));
    }

    public static function valueTypeMismatch(string $label, string $dataType): self
    {
        return new self(trans('Product::errors.value_type_mismatch', ['label' => $label, 'dataType' => $dataType]));
    }
}
