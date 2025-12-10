<?php

declare(strict_types=1);

namespace App\Core\FileManager\Mappers;

use App\Core\FileManager\DTOs\FileUpdateDTO;
use App\Core\FileManager\DTOs\FileUploadDTO;
use App\Core\FileManager\Entities\Folder;
use App\Core\FileManager\Enums\FileCollectionEnum;
use App\Utilities\OrNull;
use Illuminate\Http\Request;

class FileMapper
{
    public function fromUploadRequest(Request $request, ?Folder $folder): FileUploadDTO
    {
        return new FileUploadDTO(
            file: $request->file('file'),
            folderId: OrNull::intOrNull($request->input('folder_id')),
            moduleName: $request->input('module_name'),
            title: $request->input('title'),
            entityType: $request->input('entity_type'),
            entityId: OrNull::intOrNull($request->input('entity_id')),
            collection: $request->input('collection') ? FileCollectionEnum::from((int) $request->input('collection')) : null,
            isPublic: $request->boolean('is_public', $folder?->is_public ?? false),
            isTemporary: $request->boolean('is_temporary', false),
            expiresAt: $request->input('expires_at'),
            uploadedBy: $request->user()?->id,
        );
    }

    public function fromUpdateRequest(Request $request): FileUpdateDTO
    {
        return new FileUpdateDTO(
            title: $request->input('title'),
            folderId: OrNull::intOrNull($request->input('folder_id')),
            isPublic: OrNull::boolOrNull($request->input('is_public')),
            isActive: OrNull::boolOrNull($request->input('is_active')),
        );
    }
}
