<?php

declare(strict_types=1);

namespace App\Utilities;

class HashUtilities
{
    public static function isBcryptHash(string $hash): bool
    {
        return strlen($hash) === 60 && str_starts_with($hash, '$2y$');
    }
}
