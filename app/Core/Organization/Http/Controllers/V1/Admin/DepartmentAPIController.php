<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Controllers\V1\Admin;

use App\Contracts\Controller\BaseAPIController;
use App\Core\Organization\Http\Requests\V1\Admin\StoreDepartmentRequest;
use App\Core\Organization\Http\Requests\V1\Admin\UpdateDepartmentRequest;
use App\Core\Organization\Http\Transformers\V1\Admin\DepartmentTransformer;
use App\Core\Organization\Mappers\DepartmentMapper;
use App\Core\Organization\Repositories\DepartmentRepository;
use App\Core\Organization\Services\DepartmentService;
use Illuminate\Http\JsonResponse;

class DepartmentAPIController extends BaseAPIController
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
        $dto   = $this->mapper->fromRequest($request);
        $image = $request->file('image');

        $model = $this->service->create($dto, $image);

        return $this->response->created(
            array_merge(
                $this->transformer->transformOne($model),
                ['redirect_url' => route('web.org.departments.edit', ['department' => $model->id])]
            ),
            'دپارتمان مورد نظر ذخیره شد.'
        );
    }

    // PUT/PATCH /departments/{id}
    public function update(UpdateDepartmentRequest $request, int $id): JsonResponse
    {
        $department = $this->repository->findOrFail($id);
        $dto        = $this->mapper->fromRequestForUpdate($request, $department);
        $image      = $request->file('image');

        $updated = $this->service->update($department, $dto, $image);

        return $this->response->success(
            $this->transformer->transformOne($updated),
            'دپارتمان مورد نظر بروزرسانی شد.'
        );
    }

    // DELETE /departments/{id}
    public function destroy(int $id): JsonResponse
    {
        $department = $this->repository->findOrFail($id);
        $this->service->delete($department);

        return $this->response->success([], 'دپارتمان مورد نظر حذف شد.');
    }
}
