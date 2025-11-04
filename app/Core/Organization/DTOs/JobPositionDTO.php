<?php

declare(strict_types=1);

namespace App\Core\Organization\DTOs;

use App\Core\Organization\Enums\TierEnum;

final readonly class JobPositionDTO
{
    public function __construct(
        public string     $title,
        public ?string    $code,
        public ?TierEnum  $tier,
        public ?string    $imageUrl,
        public ?string    $description,
        public bool       $isActive,
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
