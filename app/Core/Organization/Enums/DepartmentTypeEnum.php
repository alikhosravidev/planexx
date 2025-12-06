<?php

declare(strict_types=1);

namespace App\Core\Organization\Enums;

enum DepartmentTypeEnum: int
{
    case HOLDING    = 1;
    case BRAND      = 2;
    case DEPARTMENT = 3;
    case TEAM       = 4;

    public function label(): string
    {
        return match ($this) {
            self::HOLDING    => 'هولدینگ',
            self::BRAND      => 'برند',
            self::DEPARTMENT => 'دپارتمان',
            self::TEAM       => 'تیم',
        };
    }

    public function hasImage(): bool
    {
        return match ($this) {
            self::HOLDING, self::BRAND => true,
            self::DEPARTMENT, self::TEAM => false,
        };
    }

    public function hasIconAndColor(): bool
    {
        return match ($this) {
            self::HOLDING, self::BRAND => false,
            self::DEPARTMENT, self::TEAM => true,
        };
    }
}
