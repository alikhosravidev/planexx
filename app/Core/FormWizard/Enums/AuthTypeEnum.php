<?php

declare(strict_types=1);

namespace App\Core\FormWizard\Enums;

enum AuthTypeEnum: int
{
    case None       = 0;
    case OtpAtStart = 1;
    case OtpAtEnd   = 2;

    public function label(): string
    {
        return match ($this) {
            self::None       => 'بدون احراز',
            self::OtpAtStart => 'OTP ابتدا',
            self::OtpAtEnd   => 'OTP انتها',
        };
    }
}
