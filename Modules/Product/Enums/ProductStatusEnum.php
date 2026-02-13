<?php

declare(strict_types=1);

namespace Modules\Product\Enums;

enum ProductStatusEnum: int
{
    case Active      = 1;
    case Draft       = 2;
    case Unavailable = 3;

    public function label(): string
    {
        return match ($this) {
            self::Active      => trans('Product::enums.product_status.active'),
            self::Draft       => trans('Product::enums.product_status.draft'),
            self::Unavailable => trans('Product::enums.product_status.unavailable'),
        };
    }
}
