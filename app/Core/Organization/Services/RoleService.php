<?php

declare(strict_types=1);

namespace App\Core\Organization\Services;

use App\Core\Organization\Entities\Role;
use App\Core\Organization\Repositories\RoleRepository;
use App\Domains\Role\RoleDTO;
use Illuminate\Support\Facades\DB;

readonly class RoleService
{
    public function __construct(
        private RoleRepository $roleRepository,
    ) {
    }

    public function create(RoleDTO $dto): Role
    {
        return DB::transaction(function () use ($dto) {
            $data = $dto->toArray();
            $role = $this->roleRepository->create($data);

            if (!empty($dto->permissions)) {
                $role->syncPermissions($dto->permissions);
            }

            return $role->fresh(['permissions']);
        });
    }

    public function update(Role $role, RoleDTO $dto): Role
    {
        return DB::transaction(function () use ($role, $dto) {
            $data = $dto->toArray();
            $role = $this->roleRepository->update($role->id, $data);

            if (!empty($dto->permissions)) {
                $role->syncPermissions($dto->permissions);
            }

            return $role->fresh(['permissions']);
        });
    }

    public function delete(Role $role): bool
    {
        return DB::transaction(function () use ($role) {
            $role->permissions()->detach();
            $role->users()->detach();

            return $this->roleRepository->delete($role->id);
        });
    }

    public function syncPermissions(Role $role, array $permissions): Role
    {
        $role->syncPermissions($permissions);

        return $role->fresh(['permissions']);
    }
}
