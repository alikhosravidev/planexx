<?php

declare(strict_types=1);

namespace App\Core\Organization\DTOs;

final readonly class DepartmentDTO
{
    public function __construct(
        public ?int    $parentId,
        public string $name,
        public ?string $code,
        public ?int    $managerId,
        public ?string $imageUrl,
        public ?string $description,
        public bool   $isActive,
    ) {
    }

    public function toArray(): array
    {
        return [
            'parent_id'   => $this->parentId,
            'name'        => $this->name,
            'code'        => $this->code,
            'manager_id'  => $this->managerId,
            'image_url'   => $this->imageUrl,
            'description' => $this->description,
            'is_active'   => $this->isActive,
        ];
    }
}
