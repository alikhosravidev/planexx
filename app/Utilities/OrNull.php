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

use BackedEnum;
use Carbon\Carbon;

class OrNull
{
    public static function intOrNull(null|int|string $input): ?int
    {
        if (!is_numeric($input)) {
            return null;
        }

        return (int) $input;
    }

    public static function floatOrNull(null|float|string $input): ?float
    {
        if (!is_numeric($input)) {
            return null;
        }

        return (float) $input;
    }

    public static function stringOrNull(null|int|string $input): ?string
    {
        if (null === $input) {
            return null;
        }

        return (string) $input;
    }

    public static function boolOrNull(null|int|bool|string $input): ?bool
    {
        if (null === $input) {
            return false;
        }

        if (is_numeric($input)) {
            return (bool) $input;
        }

        if (is_string($input)) {
            if ($input === 'true') {
                return true;
            }

            if ($input === 'false') {
                return false;
            }

            return null;
        }

        return (bool) $input;
    }

    public static function dateOrNull(null|string|\DateTime $input): ?Carbon
    {
        if (null === $input) {
            return null;
        }

        return Carbon::parse($input);
    }

    public static function enumOrNull(null|string|int|BackedEnum $input, string $enumClass): ?BackedEnum
    {
        if (null === $input) {
            return null;
        }

        if ($input instanceof BackedEnum) {
            return $input;
        }

        return $enumClass::from($input);
    }

    public static function valueObjectOrNull(null|string|int $input, string $valueObjectClass): ?object
    {
        if (null === $input) {
            return null;
        }

        return new $valueObjectClass($input);
    }
}
