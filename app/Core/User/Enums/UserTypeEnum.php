<?php

declare(strict_types=1);

namespace App\Core\User\Enums;

enum UserTypeEnum: int
{
    case USER     = 1;
    case EMPLOYEE = 2;
    case CUSTOMER = 3;

    public function label(): string
    {
        return match ($this) {
            self::USER     => 'user',
            self::EMPLOYEE => 'employee',
            self::CUSTOMER => 'customer',
        };
    }
}
