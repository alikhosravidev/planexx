<?php

declare(strict_types=1);

namespace App\Core\Organization\Enums;

enum GenderEnum: int
{
    case MALE   = 1;
    case FEMALE = 2;
    case OTHER  = 3;

    public function label(): string
    {
        return match ($this) {
            self::MALE   => 'آقا',
            self::FEMALE => 'خانوم',
            self::OTHER  => 'نامشخص',
        };
    }
}
