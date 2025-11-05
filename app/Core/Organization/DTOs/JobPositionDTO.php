<?php

declare(strict_types=1);

namespace App\Core\Organization\DTOs;

use App\Core\Organization\Enums\TierEnum;
use Illuminate\Contracts\Support\Arrayable;

final readonly class JobPositionDTO implements Arrayable
{
    public function __construct(
        public string    $title,
        public ?string   $code = null,
        public ?TierEnum $tier = null,
        public ?string   $imageUrl = null,
        public ?string   $description = null,
        public bool      $isActive = true,
    ) {
    }

    public function toArray(): array
    {
        return [
            'title'       => $this->title,
            'code'        => $this->code,
            'tier'        => $this->tier?->value,
            'image_url'   => $this->imageUrl,
            'description' => $this->description,
            'is_active'   => $this->isActive,
        ];
    }
}
