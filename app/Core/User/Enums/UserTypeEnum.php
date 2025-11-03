<?php

declare(strict_types=1);

namespace App\Core\User\Enums;

enum UserTypeEnum: int
{
    case User     = 1;
    case Employee = 2;
    case Customer = 3;

    public function label(): string
    {
        return match ($this) {
            self::User     => 'user',
            self::Employee => 'employee',
            self::Customer => 'customer',
        };
    }
}
