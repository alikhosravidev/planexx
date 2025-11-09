<?php

declare(strict_types=1);

namespace App\Core\BPMS\Enums;

enum TaskPriority: int
{
    case LOW    = 0;
    case MEDIUM = 1;
    case HIGH   = 2;
    case URGENT = 3;

    public function label(): string
    {
        return match ($this) {
            self::LOW    => 'کم',
            self::MEDIUM => 'متوسط',
            self::HIGH   => 'بالا',
            self::URGENT => 'فوری',
        };
    }
}
