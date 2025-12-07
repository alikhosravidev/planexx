<?php

declare(strict_types=1);

namespace App\Domains\Department;

use App\Contracts\DataTransferObject;
use App\Core\Organization\Enums\DepartmentTypeEnum;

final readonly class DepartmentDTO implements DataTransferObject
{
    public function __construct(
        public string  $name,
        public ?int    $parentId = null,
        public ?string $code = null,
        public ?int    $managerId = null,
        public ?string $color = null,
        public ?string $icon = null,
        public ?string $description = null,
        public DepartmentTypeEnum $type = DepartmentTypeEnum::DEPARTMENT,
        public bool    $isActive = true,
    ) {
    }

    public function toArray(): array
    {
        return [
            'parent_id'   => $this->parentId,
            'name'        => $this->name,
            'code'        => $this->code,
            'manager_id'  => $this->managerId,
            'color'       => $this->color,
            'icon'        => $this->icon,
            'description' => $this->description,
            'type'        => $this->type->value,
            'is_active'   => $this->isActive,
        ];
    }
}
