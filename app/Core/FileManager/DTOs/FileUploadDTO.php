<?php

declare(strict_types=1);

namespace App\Core\FileManager\DTOs;

use App\Core\FileManager\Enums\FileCollectionEnum;
use Illuminate\Http\UploadedFile;

final readonly class FileUploadDTO
{
    public function __construct(
        public UploadedFile        $file,
        public ?int                $folderId = null,
        public ?string             $moduleName = null,
        public ?string             $title = null,
        public ?string             $entityType = null,
        public ?int                $entityId = null,
        public ?FileCollectionEnum $collection = null,
        public bool                $isPublic = false,
        public bool                $isTemporary = false,
        public ?string             $expiresAt = null,
        public ?int                $uploadedBy = null,
    ) {
    }
}
