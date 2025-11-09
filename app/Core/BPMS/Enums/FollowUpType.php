<?php

declare(strict_types=1);

namespace App\Core\BPMS\Enums;

enum FollowUpType: int
{
    case FOLLOW_UP        = 0;
    case STATE_TRANSITION = 1;
    case USER_ACTION      = 2;
    case WATCHER_REVIEW   = 3;

    public function label(): string
    {
        return match ($this) {
            self::FOLLOW_UP        => 'پیگیری',
            self::STATE_TRANSITION => 'تغییر مرحله',
            self::USER_ACTION      => 'اقدام کاربر',
            self::WATCHER_REVIEW   => 'بررسی ناظر',
        };
    }
}
