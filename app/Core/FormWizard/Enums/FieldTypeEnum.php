<?php

declare(strict_types=1);

namespace App\Core\FormWizard\Enums;

enum FieldTypeEnum: int
{
    case Text          = 0;
    case Number        = 1;
    case Date          = 2;
    case Textarea      = 3;
    case Radio         = 4;
    case Checkbox      = 5;
    case Toggle        = 6;
    case Select        = 7;
    case File          = 8;
    case VoiceRecorder = 9;

    public function label(): string
    {
        return match ($this) {
            self::Text          => 'متن',
            self::Number        => 'عدد',
            self::Date          => 'تاریخ',
            self::Textarea      => 'متن طولانی',
            self::Radio         => 'رادیو',
            self::Checkbox      => 'چک‌باکس',
            self::Toggle        => 'تغییر وضعیت',
            self::Select        => 'انتخاب',
            self::File          => 'فایل',
            self::VoiceRecorder => 'ضبط صدا',
        };
    }
}
