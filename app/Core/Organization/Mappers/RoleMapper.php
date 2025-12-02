<?php

declare(strict_types=1);

namespace App\Core\Organization\Mappers;

use App\Core\Organization\Entities\Role;
use App\Domains\Role\RoleDTO;
use Illuminate\Http\Request;

class RoleMapper
{
    public function fromRequest(Request $request): RoleDTO
    {
        return new RoleDTO(
            name       : $request->input('name'),
            title      : $request->input('title'),
            guardName  : $request->input('guard_name', 'web'),
            permissions: $request->input('permissions', []),
        );
    }

    public function fromRequestForUpdate(Request $request, Role $role): RoleDTO
    {
        return new RoleDTO(
            name       : $request->input('name')       ?? $role->name,
            title      : $request->input('title')      ?? $role->title,
            guardName  : $request->input('guard_name') ?? $role->guard_name,
            permissions: $request->input('permissions', []),
        );
    }
}
