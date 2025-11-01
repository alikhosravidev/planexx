<?php

declare(strict_types=1);

namespace App\Core\User\Enums;

enum CustomerTypeEnum: int
{
    case B2C = 1;
    case B2B = 2;
    case B2G = 3;

    public function label(): string
    {
        return match ($this) {
            self::B2C => 'b2c',
            self::B2B => 'b2b',
            self::B2G => 'b2g',
        };
    }
}
