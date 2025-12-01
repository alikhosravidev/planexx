<?php

declare(strict_types=1);

namespace App\Core\Organization\Mappers;

use App\Core\Organization\Entities\Department;
use App\Domains\Department\DepartmentDTO;
use App\Utilities\OrNull;
use Illuminate\Http\Request;

class DepartmentMapper
{
    public function fromRequest(Request $request): DepartmentDTO
    {
        return new DepartmentDTO(
            name       : $request->input('name'),
            parentId   : OrNull::intOrNull($request->input('parent_id')),
            code       : $request->input('code'),
            managerId  : OrNull::intOrNull($request->input('manager_id')),
            imageUrl   : $request->input('image_url'),
            color      : $request->input('color'),
            icon       : $request->input('icon'),
            description: $request->input('description'),
            isActive   : $request->boolean('is_active', true),
        );
    }

    public function fromRequestForUpdate(Request $request, Department $department): DepartmentDTO
    {
        return new DepartmentDTO(
            name       : $request->input('name')                          ?? $department->name,
            parentId   : OrNull::intOrNull($request->input('parent_id'))  ?? $department->parent_id,
            code       : $request->input('code')                          ?? $department->code,
            managerId  : OrNull::intOrNull($request->input('manager_id')) ?? $department->manager_id,
            imageUrl   : $request->input('image_url')                     ?? $department->image_url,
            color      : $request->input('color')                         ?? $department->color,
            icon       : $request->input('icon')                          ?? $department->icon,
            description: $request->input('description')                   ?? $department->description,
            isActive   : $request->boolean('is_active', $department->is_active),
        );
    }
}
