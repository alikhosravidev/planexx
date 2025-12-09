<?php

declare(strict_types=1);

namespace App\Core\FileManager\DTOs;

use Illuminate\Contracts\Support\Arrayable;

final readonly class FolderDTO implements Arrayable
{
    public ?string $color;
    public function __construct(
        public string  $name,
        public ?string $moduleName = null,
        public ?int    $parentId = null,
        public bool    $isPublic = false,
        ?string $color = null,
        public ?string $icon = null,
        public ?string $description = null,
        public int     $order = 0,
    ) {
        $this->color = null !== $color ? explode('-', $color)[0] : null;
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
