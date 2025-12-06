<?php

declare(strict_types=1);

namespace App\Core\FileManager\DTOs;

use Illuminate\Contracts\Support\Arrayable;

final readonly class FileUpdateDTO implements Arrayable
{
    public function __construct(
        public ?string $title = null,
        public ?int    $folderId = null,
        public ?bool   $isPublic = null,
        public ?bool   $isActive = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'title'     => $this->title,
            'folder_id' => $this->folderId,
            'is_public' => $this->isPublic,
            'is_active' => $this->isActive,
        ];
    }
}
