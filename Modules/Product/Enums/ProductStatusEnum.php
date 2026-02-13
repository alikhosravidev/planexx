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
        $name = strtolower($this->name);

        return trans("Product::enums.product_status.{$name}");
    }

    public function variant(): string
    {
        return match ($this) {
            self::Active      => 'success',
            self::Draft       => 'warning',
            self::Unavailable => 'danger',
        };
    }
}
