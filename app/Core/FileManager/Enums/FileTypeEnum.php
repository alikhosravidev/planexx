<?php

declare(strict_types=1);

namespace App\Core\FileManager\Enums;

enum FileTypeEnum: int
{
    case IMAGE    = 0;
    case VIDEO    = 1;
    case AUDIO    = 2;
    case DOCUMENT = 3;
    case ARCHIVE  = 4;
    case OTHER    = 5;

    public function label(): string
    {
        return match ($this) {
            self::IMAGE    => 'image',
            self::VIDEO    => 'video',
            self::AUDIO    => 'audio',
            self::DOCUMENT => 'document',
            self::ARCHIVE  => 'archive',
            self::OTHER    => 'other',
        };
    }
}
