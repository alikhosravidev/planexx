<?php

declare(strict_types=1);

namespace Modules\Product\Enums;

enum AttributeDataTypeEnum: int
{
    case Text    = 1;
    case Number  = 2;
    case Date    = 3;
    case Boolean = 4;
    case Select  = 5;

    public function label(): string
    {
        return match ($this) {
            self::Text    => trans('Product::enums.attribute_data_type.text'),
            self::Number  => trans('Product::enums.attribute_data_type.number'),
            self::Date    => trans('Product::enums.attribute_data_type.date'),
            self::Boolean => trans('Product::enums.attribute_data_type.boolean'),
            self::Select  => trans('Product::enums.attribute_data_type.select'),
        };
    }
}
