<?php

declare(strict_types=1);

namespace App\Core\Organization\Enums;

enum TierEnum: int
{
    case INVESTOR          = 0;
    case SENIOR_MANAGEMENT = 1;
    case MIDDLE_MANAGEMENT = 2;
    case STAFF             = 3;

    public function label(): string
    {
        return match ($this) {
            self::INVESTOR          => 'سرمایه گذار',
            self::SENIOR_MANAGEMENT => 'مدیریت ارشد',
            self::MIDDLE_MANAGEMENT => 'مدیریت میانی',
            self::STAFF             => 'کارکنان',
        };
    }
}
