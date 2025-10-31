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

class StringUtility
{
    public static function transformMobile(string $mobile): string
    {
        $mobile = str_replace(['+980', '+098', '+98'], ['0', '0', '0'], StringUtility::numberToEn(trim($mobile)));
        $mobile = self::strReplaceStart('0098', '09', (string) $mobile);
        $mobile = self::strReplaceStart('009', '09', (string) $mobile);

        $mobile = preg_replace('/[^0-9]/', '', $mobile);

        if (!str_starts_with($mobile, '0') && 10 === \strlen($mobile)) {
            $mobile = '0' . $mobile;
        }

        return $mobile;
    }

    public static function strReplaceStart(string $search, string $replace, string $haystack): string
    {
        $pos = strpos($haystack, $search);

        if ($pos === 0) {
            return substr_replace($haystack, $replace, $pos, strlen($search));
        }

        return $haystack;
    }

    public static function fetchNumbers(string $string): string
    {
        return preg_replace('/\D/', '', $string);
    }

    public static function numberToEn($string): string
    {
        return strtr(
            (string) $string,
            [
                '۰' => '0',
                '۱' => '1',
                '۲' => '2',
                '۳' => '3',
                '۴' => '4',
                '۵' => '5',
                '۶' => '6',
                '۷' => '7',
                '۸' => '8',
                '۹' => '9',
                '٠' => '0',
                '١' => '1',
                '٢' => '2',
                '٣' => '3',
                '٤' => '4',
                '٥' => '5',
                '٦' => '6',
                '٧' => '7',
                '٨' => '8',
                '٩' => '9',
            ]
        );
    }
}
