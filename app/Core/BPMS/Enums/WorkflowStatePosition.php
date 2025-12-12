<?php

declare(strict_types=1);

namespace App\Core\BPMS\Enums;

enum WorkflowStatePosition: int
{
    case Start        = 0;
    case Middle       = 1;
    case FinalSuccess = 2;
    case FinalFailed  = 3;
    case FinalClosed  = 4;

    public function label(): string
    {
        return match ($this) {
            self::Start        => 'آغازین',
            self::Middle       => 'میانی',
            self::FinalSuccess => 'پایانی موفق',
            self::FinalFailed  => 'پایانی ناموفق',
            self::FinalClosed  => 'بسته شده',
        };
    }

    public function isFinal(): bool
    {
        return str_starts_with($this->name, 'Final');
    }
}
