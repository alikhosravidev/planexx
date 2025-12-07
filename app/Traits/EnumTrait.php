<?php

declare(strict_types=1);

namespace App\Traits;

trait EnumTrait
{
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
