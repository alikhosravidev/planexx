<?php

declare(strict_types=1);

namespace App\Core\Organization\Mappers;

use App\Core\Organization\DTOs\DepartmentDTO;
use App\Core\Organization\Entities\Department;
use Illuminate\Http\Request;

class DepartmentMapper
{
    public function fromRequest(Request $request): DepartmentDTO
    {
        return new DepartmentDTO(
            parentId: $request->input('parent_id'),
            name: $request->input('name'),
            code: $request->input('code'),
            managerId: $request->input('manager_id'),
            imageUrl: $request->input('image_url'),
            description: $request->input('description'),
            isActive: $request->boolean('is_active', true),
        );
    }

    public function fromRequestForUpdate(Request $request, Department $department): DepartmentDTO
    {
        return new DepartmentDTO(
            parentId: $request->input('parent_id')      ?? $department->parent_id,
            name: $request->input('name')               ?? $department->name,
            code: $request->input('code')               ?? $department->code,
            managerId: $request->input('manager_id')    ?? $department->manager_id,
            imageUrl: $request->input('image_url')      ?? $department->image_url,
            description: $request->input('description') ?? $department->description,
            isActive: $request->boolean('is_active', $department->is_active),
        );
    }
}
