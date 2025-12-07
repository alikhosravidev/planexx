<?php

declare(strict_types=1);

namespace App\Core\Organization\Enums;

use App\Traits\EnumTrait;

enum DepartmentTypeEnum: int
{
    use EnumTrait;

    case HOLDING    = 1;
    case BRAND      = 2;
    case DEPARTMENT = 3;
    case TEAM       = 4;

    public function label(): string
    {
        return match ($this) {
            self::HOLDING    => 'هولدینگ',
            self::BRAND      => 'برند / گروه',
            self::DEPARTMENT => 'دپارتمان / شعبه',
            self::TEAM       => 'تیم',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::HOLDING    => 'purple',
            self::BRAND      => 'orange',
            self::DEPARTMENT => 'blue',
            self::TEAM       => 'green',
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
