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

class Lang
{
    public static function toPersian(float|int|string $number): string
    {
        $faName = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $enName = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($enName, $faName, (string) $number);
    }

    public static function toEnglish(float|int|string $number): int
    {
        $faName = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $enName = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return (int) str_replace($faName, $enName, (string) $number);
    }

    public static function toPersianPrice(float|int|string $price): string
    {
        return self::toPersian(number_format($price));
    }
}
