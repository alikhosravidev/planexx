<?php

declare(strict_types=1);

namespace Modules\Product\DTOs;

use Illuminate\Contracts\Support\Arrayable;
use Modules\Product\ValueObjects\CategoryId;

final readonly class CategoryDTO implements Arrayable
{
    public function __construct(
        public string $name,
        public ?string $slug = null,
        public ?CategoryId $parentId = null,
        public ?string $iconClass = null,
        public int $sortOrder = 0,
        public bool $isActive = true,
    ) {
    }

    public function toArray(): array
    {
        return [
            'name'       => $this->name,
            'slug'       => $this->slug,
            'parent_id'  => $this->parentId?->value,
            'icon_class' => $this->iconClass,
            'sort_order' => $this->sortOrder,
            'is_active'  => $this->isActive,
        ];
    }
}
