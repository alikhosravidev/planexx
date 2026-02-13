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
        $name = strtolower($this->name);

        return trans("Product::enums.attribute_data_type.{$name}");
    }
}
