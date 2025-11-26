<?php

declare(strict_types=1);

namespace App\Core\Organization\Services\Auth\Enums;

enum IdentifierType: string
{
    case Email    = 'email';
    case Mobile   = 'mobile';
    case Username = 'username';

    public function isUsername(): bool
    {
        return $this === self::Username;
    }

    public function isEmail(): bool
    {
        return $this === self::Email;
    }

    public function isMobile(): bool
    {
        return $this === self::Mobile;
    }

    public function toPersian(): string
    {
        return match ($this) {
            self::Email    => 'ایمیل',
            self::Mobile   => 'موبایل',
            self::Username => 'نام کاربری',
        };
    }
}
