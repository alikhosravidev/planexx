<?php

declare(strict_types=1);

namespace App\Core\Organization\Enums;

enum UserTypeEnum: int
{
    case User     = 1;
    case Employee = 2;
    case Customer = 3;

    public function label(): string
    {
        return match ($this) {
            self::User     => 'کاربر',
            self::Employee => 'کارمند',
            self::Customer => 'مشتری',
        };
    }

    public function plural(): string
    {
        return match ($this) {
            self::User     => 'کاربران عادی',
            self::Employee => 'کارکنان',
            self::Customer => 'مشتریان',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::User     => 'fa-user',
            self::Employee => 'fa-user-tie',
            self::Customer => 'fa-users',
        };
    }

    public static function fromName(string $name): ?self
    {
        foreach (self::cases() as $case) {
            if ($name === $case->name) {
                return $case;
            }
        }

        return null;
    }
}
