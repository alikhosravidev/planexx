<?php

declare(strict_types=1);

namespace App\DTOs;

use Illuminate\Contracts\Support\Arrayable;

final readonly class TagDTO implements Arrayable
{
    public function __construct(
        public string  $name,
        public ?string $slug = null,
        public ?string $description = null,
        public ?string $color = null,
        public ?string $icon = null,
        public int     $usageCount = 0,
    ) {
    }

    public function toArray(): array
    {
        return [
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'color'       => $this->color,
            'icon'        => $this->icon,
            'usage_count' => $this->usageCount,
        ];
    }
}
