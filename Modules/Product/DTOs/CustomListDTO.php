<?php

declare(strict_types=1);

namespace Modules\Product\DTOs;

use Illuminate\Contracts\Support\Arrayable;
use Modules\Product\Collections\CustomListAttributeDTOCollection;

final readonly class CustomListDTO implements Arrayable
{
    public function __construct(
        public string $title,
        public ?string $slug = null,
        public ?string $description = null,
        public string $iconClass = 'fa-solid fa-clipboard-list',
        public string $color = 'blue',
        public bool $isActive = true,
        public CustomListAttributeDTOCollection $attributes = new CustomListAttributeDTOCollection(),
    ) {
    }

    public function toArray(): array
    {
        return [
            'title'       => $this->title,
            'slug'        => $this->slug,
            'description' => $this->description,
            'icon_class'  => $this->iconClass,
            'color'       => $this->color,
            'is_active'   => $this->isActive,
        ];
    }
}
