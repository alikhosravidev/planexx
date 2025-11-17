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

class NationalCodeValidator
{
    public static function isValid(?string $nationalCode): bool
    {
        if (empty($nationalCode)) {
            return false;
        }

        // Check if the national code is a 10-digit numeric value
        if (!self::isValidFormat($nationalCode)) {
            return false;
        }

        // Check if all digits are not the same
        if (self::hasRepeatedDigits($nationalCode)) {
            return false;
        }

        // Calculate the sum based on the algorithm
        $sum = self::calculateSum($nationalCode);

        // Calculate the remainder
        $remainder = $sum % 11;

        // Check parity
        $lastDigit = (int) $nationalCode[9];

        if (self::checkParity($remainder, $lastDigit)) {
            return true;
        }

        return false;
    }

    private static function isValidFormat(string $nationalCode): bool
    {
        return preg_match('/^[0-9]{10}$/', $nationalCode) > 0;
    }

    private static function hasRepeatedDigits(string $nationalCode): bool
    {
        return preg_match('/^(\d)\1{9}$/', $nationalCode) > 0;
    }

    private static function calculateSum(string $nationalCode): int
    {
        $sum = 0;
        for ($i = 0; $i < 9; ++$i) {
            $sum += (10 - $i) * (int) $nationalCode[$i];
        }

        return $sum;
    }

    private static function checkParity(int $remainder, int $lastDigit): bool
    {
        if ($remainder < 2 && $remainder === $lastDigit) {
            return true;
        }

        return $remainder >= 2 && $remainder === 11 - $lastDigit;
    }
}
