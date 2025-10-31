<?php

declare(strict_types=1);

/*
 * This file is part of the LSP API and Panels projects
 *
 * Copyright (c) >= 2023 LSP
 *
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 * Please follow OOP, SOLID and linux philosophy in development and becarefull about anti-patterns
 *
 * @CTO Mehrdad Dadkhah <dadkhah.ir@gmail.com>
 */

namespace App\Utilities;

class CustomValidator
{
    public const EMAIL_REGEX = '/^(?i)[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$/';

    public const MOBILE_REGEX = '/^(\\+98|0)?9\\d{9}$/';

    public static function isMobileValid(?string $mobile): bool
    {
        if (empty($mobile)) {
            return false;
        }

        return (bool) preg_match(self::MOBILE_REGEX, $mobile);
    }

    public static function isEmailValid(?string $email): bool
    {
        if (empty($email)) {
            return false;
        }

        return (bool) preg_match(self::EMAIL_REGEX, $email);
    }

    public static function isJalaliDateValid(string $date): bool
    {
        $pattern = '/^(13|14)\d{2}\/(0[1-9]|1[0-2])\/(0[1-9]|[12]\d|3[01])$/';

        return (bool) preg_match($pattern, $date);
    }
}
