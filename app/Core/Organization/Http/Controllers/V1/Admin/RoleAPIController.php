<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Controllers\V1\Admin;

use App\Contracts\Controller\BaseAPIController;
use App\Core\Organization\Http\Requests\V1\Admin\StoreRoleRequest;
use App\Core\Organization\Http\Requests\V1\Admin\UpdateRoleRequest;
use App\Core\Organization\Http\Transformers\V1\Admin\RoleTransformer;
use App\Core\Organization\Mappers\RoleMapper;
use App\Core\Organization\Repositories\RoleRepository;
use App\Core\Organization\Services\RoleService;
use Illuminate\Http\JsonResponse;

class RoleAPIController extends BaseAPIController
{
    public function __construct(
        RoleRepository               $repository,
        RoleTransformer              $transformer,
        private readonly RoleService $service,
        private readonly RoleMapper  $mapper,
    ) {
        parent::__construct($repository, $transformer);
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        $dto  = $this->mapper->fromRequest($request);
        $role = $this->service->create($dto);

        return $this->response->created(
            array_merge(
                $this->transformer->transformOne($role),
                ['redirect_url' => route('web.org.roles.permissions', $role->id)]
            ),
            'نقش با موفقیت ایجاد شد.'
        );
    }

    public function update(UpdateRoleRequest $request, int $id): JsonResponse
    {
        $role    = $this->repository->findOrFail($id);
        $dto     = $this->mapper->fromRequestForUpdate($request, $role);
        $updated = $this->service->update($role, $dto);

        return $this->response->success(
            $this->transformer->transformOne($updated),
            'نقش با موفقیت بروزرسانی شد.'
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $role = $this->repository->findOrFail($id);
        $this->service->delete($role);

        return $this->response->success([], 'نقش مورد نظر حذف شد.');
    }
}
