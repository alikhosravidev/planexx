<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Controllers\API\V1\Admin;

use App\Contracts\Controller\BaseController;
use App\Core\Organization\Http\Requests\V1\Admin\StoreDepartmentRequest;
use App\Core\Organization\Http\Requests\V1\Admin\UpdateDepartmentRequest;
use App\Core\Organization\Http\Transformers\V1\Admin\DepartmentTransformer;
use App\Core\Organization\Mappers\DepartmentMapper;
use App\Core\Organization\Repositories\DepartmentRepository;
use App\Core\Organization\Services\DepartmentService;
use Illuminate\Http\JsonResponse;

class DepartmentController extends BaseController
{
    public function __construct(
        DepartmentRepository               $repository,
        DepartmentTransformer              $transformer,
        private readonly DepartmentService $service,
        private readonly DepartmentMapper  $mapper,
    ) {
        parent::__construct($repository, $transformer);
    }

    // POST /departments
    public function store(StoreDepartmentRequest $request): JsonResponse
    {
        $dto = $this->mapper->fromRequest($request);

        $model = $this->service->create($dto);

        return $this->response->created(
            $this->transformer->transformOne($model)
        );
    }

    // PUT/PATCH /departments/{id}
    public function update(UpdateDepartmentRequest $request, int $id): JsonResponse
    {
        $department = $this->repository->findOrFail($id);
        $dto        = $this->mapper->fromRequestForUpdate($request, $department);

        $updated = $this->service->update($department, $dto);

        return $this->response->success(
            $this->transformer->transformOne($updated)
        );
    }

    // DELETE /departments/{id}
    public function destroy(int $id): JsonResponse
    {
        $department = $this->repository->findOrFail($id);
        $this->service->delete($department);

        return $this->response->success([]);
    }
}
