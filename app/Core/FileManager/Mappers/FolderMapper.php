<?php

declare(strict_types=1);

namespace App\Core\FileManager\Mappers;

use App\Core\FileManager\DTOs\FolderDTO;
use App\Core\FileManager\Entities\Folder;
use Illuminate\Http\Request;

class FolderMapper
{
    public function fromRequest(Request $request): FolderDTO
    {
        return new FolderDTO(
            name: $request->input('name'),
            moduleName: $request->input('module_name'),
            parentId: $request->filled('parent_id') ? (int) $request->input('parent_id') : null,
            isPublic: $request->boolean('is_public', false),
            color: $request->input('color'),
            icon: $request->input('icon'),
            description: $request->input('description'),
            order: $request->integer('order', 0),
        );
    }

    public function fromRequestForUpdate(Request $request, Folder $folder): FolderDTO
    {
        return new FolderDTO(
            name: $request->input('name')              ?? $folder->name,
            moduleName: $request->input('module_name') ?? $folder->module_name,
            parentId: $request->filled('parent_id') ? (int) $request->input('parent_id') : $folder->parent_id,
            isPublic: $request->boolean('is_public', $folder->is_public),
            color: $request->input('color')             ?? $folder->color,
            icon: $request->input('icon')               ?? $folder->icon,
            description: $request->input('description') ?? $folder->description,
            order: $request->integer('order', $folder->order),
        );
    }
}
