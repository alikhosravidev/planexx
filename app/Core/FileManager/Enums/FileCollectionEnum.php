<?php

declare(strict_types=1);

namespace App\Core\FileManager\Enums;

enum FileCollectionEnum: int
{
    case AVATAR     = 0;
    case DOCUMENT   = 1;
    case ATTACHMENT = 2;
    case THUMBNAIL  = 3;
    case TEMP       = 4;
    case OTHER      = 5;

    public function label(): string
    {
        return match ($this) {
            self::AVATAR     => 'avatar',
            self::DOCUMENT   => 'document',
            self::ATTACHMENT => 'attachment',
            self::THUMBNAIL  => 'thumbnail',
            self::TEMP       => 'temp',
            self::OTHER      => 'other',
        };
    }
}
