<?php

declare(strict_types=1);

namespace App\Core\FileManager\DTOs;

use Illuminate\Contracts\Support\Arrayable;

final readonly class FolderDTO implements Arrayable
{
    public function __construct(
        public string  $name,
        public ?string $moduleName = null,
        public ?int    $parentId = null,
        public bool    $isPublic = false,
        public ?string $color = null,
        public ?string $icon = null,
        public ?string $description = null,
        public int     $order = 0,
    ) {
    }

    public function toArray(): array
    {
        return [
            'name'        => $this->name,
            'module_name' => $this->moduleName,
            'parent_id'   => $this->parentId,
            'is_public'   => $this->isPublic,
            'color'       => $this->color,
            'icon'        => $this->icon,
            'description' => $this->description,
            'order'       => $this->order,
        ];
    }
}
