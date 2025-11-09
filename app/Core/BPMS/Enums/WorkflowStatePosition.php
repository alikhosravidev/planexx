<?php

declare(strict_types=1);

namespace App\Core\BPMS\Enums;

enum WorkflowStatePosition: int
{
    case START         = 0;
    case MIDDLE        = 1;
    case FINAL_SUCCESS = 2;
    case FINAL_FAILED  = 3;
    case FINAL_CLOSED  = 4;

    public function label(): string
    {
        return match ($this) {
            self::START         => 'آغازین',
            self::MIDDLE        => 'میانی',
            self::FINAL_SUCCESS => 'پایانی موفق',
            self::FINAL_FAILED  => 'پایانی ناموفق',
            self::FINAL_CLOSED  => 'بسته شده',
        };
    }
}
