<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Controllers\V1\Admin;

use App\Contracts\Controller\BaseAPIController;
use App\Core\Organization\Http\Requests\V1\Admin\UpdateUserRolesRequest;
use App\Core\Organization\Http\Transformers\V1\Admin\UserTransformer;
use App\Core\Organization\Repositories\UserRepository;
use App\Core\Organization\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserRoleAdminController extends BaseAPIController
{
    public function __construct(
        UserRepository                 $repository,
        UserTransformer                $transformer,
        private readonly RoleService   $roleService,
    ) {
        parent::__construct($repository, $transformer);
    }

    public function show(int|string $id, Request $request): JsonResponse
    {
        $entity = $this->repository->newQuery()->with('roles')->findOrFail($id);
        $roles  = $entity->roles;

        return $this->response->success([
            'primary_role'    => $roles->where('pivot.is_primary', '=', true)->pluck('id')->first(),
            'secondary_roles' => $roles->where('pivot.is_primary', '=', false)->pluck('id')->values()->all(),
        ]);
    }

    public function update(UpdateUserRolesRequest $request, int $user): JsonResponse
    {
        $entity = $this->repository->findOrFail($user);

        $primary   = $request->validated('primary_role');
        $secondary = $request->validated('secondary_roles', []);

        $updated = $this->roleService->updateUserRoles($entity, (int) $primary, $secondary);

        return $this->response->success(
            $this->transformer->transformOne($updated),
            'نقش‌های کاربر بروزرسانی شد.'
        );
    }
}
