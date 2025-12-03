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
        $data = [];

        if ($this->title !== null) {
            $data['title'] = $this->title;
        }

        if ($this->folderId !== null) {
            $data['folder_id'] = $this->folderId;
        }

        if ($this->isPublic !== null) {
            $data['is_public'] = $this->isPublic;
        }

        if ($this->isActive !== null) {
            $data['is_active'] = $this->isActive;
        }

        return $data;
    }
}
