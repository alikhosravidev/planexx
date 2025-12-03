<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Controllers\API\V1\Admin;

use App\Contracts\Controller\BaseAPIController;
use App\Core\Organization\Http\Requests\V1\Admin\UpdateUserRolesRequest;
use App\Core\Organization\Http\Transformers\V1\Admin\UserTransformer;
use App\Core\Organization\Repositories\UserRepository;
use App\Core\Organization\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserRoleAPIController extends BaseAPIController
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
        $entity  = $this->repository->newQuery()->with('roles')->findOrFail($id);
        $roleIds = $entity->roles->pluck('id')->values();

        $primary   = $roleIds->first();
        $secondary = $roleIds->slice(1)->values()->all();

        return $this->response->success([
            'primary_role'    => $primary,
            'secondary_roles' => $secondary,
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
