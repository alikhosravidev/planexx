<?php

declare(strict_types=1);

namespace App\Core\FormWizard\Enums;

enum DisplayModeEnum: int
{
    case SinglePage = 0;
    case MultiStep  = 1;

    public function label(): string
    {
        return match ($this) {
            self::SinglePage => 'یک مرحله‌ای',
            self::MultiStep  => 'چند مرحله‌ای',
        };
    }
}
