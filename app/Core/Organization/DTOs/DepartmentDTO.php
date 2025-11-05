<?php

declare(strict_types=1);

namespace App\Core\Organization\DTOs;

use Illuminate\Contracts\Support\Arrayable;

final readonly class DepartmentDTO implements Arrayable
{
    public function __construct(
        public string  $name,
        public ?int    $parentId = null,
        public ?string $code = null,
        public ?int    $managerId = null,
        public ?string $imageUrl = null,
        public ?string $description = null,
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
            'image_url'   => $this->imageUrl,
            'description' => $this->description,
            'is_active'   => $this->isActive,
        ];
    }
}
