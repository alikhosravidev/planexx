<?php

declare(strict_types=1);

namespace App\Core\BPMS\Enums;

enum WatchStatus: int
{
    case OPEN     = 0;
    case REVIEWED = 1;

    public function label(): string
    {
        return match ($this) {
            self::OPEN     => 'باز',
            self::REVIEWED => 'بررسی‌شده',
        };
    }
}
