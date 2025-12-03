<?php

declare(strict_types=1);

namespace App\Core\FileManager\Mappers;

use App\Core\FileManager\DTOs\FileUpdateDTO;
use App\Core\FileManager\DTOs\FileUploadDTO;
use App\Core\FileManager\Enums\FileCollectionEnum;
use Illuminate\Http\Request;

class FileMapper
{
    public function fromUploadRequest(Request $request): FileUploadDTO
    {
        return new FileUploadDTO(
            file: $request->file('file'),
            folderId: $request->filled('folder_id') ? (int) $request->input('folder_id') : null,
            moduleName: $request->input('module_name'),
            title: $request->input('title'),
            entityType: $request->input('entity_type'),
            entityId: $request->filled('entity_id') ? (int) $request->input('entity_id') : null,
            collection: $request->input('collection') ? FileCollectionEnum::from((int) $request->input('collection')) : null,
            isPublic: $request->boolean('is_public', false),
            isTemporary: $request->boolean('is_temporary', false),
            expiresAt: $request->input('expires_at'),
            uploadedBy: $request->user()?->id,
        );
    }

    public function fromUpdateRequest(Request $request): FileUpdateDTO
    {
        return new FileUpdateDTO(
            title: $request->input('title'),
            folderId: $request->filled('folder_id') ? (int) $request->input('folder_id') : null,
            isPublic: $request->has('is_public') ? $request->boolean('is_public') : null,
            isActive: $request->has('is_active') ? $request->boolean('is_active') : null,
        );
    }
}
