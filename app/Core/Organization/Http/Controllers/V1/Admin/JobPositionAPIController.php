<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Controllers\V1\Admin;

use App\Contracts\Controller\BaseAPIController;
use App\Core\Organization\Http\Requests\V1\Admin\StoreJobPositionRequest;
use App\Core\Organization\Http\Requests\V1\Admin\UpdateJobPositionRequest;
use App\Core\Organization\Http\Transformers\V1\Admin\JobPositionTransformer;
use App\Core\Organization\Mappers\JobPositionMapper;
use App\Core\Organization\Repositories\JobPositionRepository;
use App\Core\Organization\Services\JobPositionService;
use Illuminate\Http\JsonResponse;

class JobPositionAPIController extends BaseAPIController
{
    public function __construct(
        JobPositionRepository               $repository,
        JobPositionTransformer              $transformer,
        private readonly JobPositionService $service,
        private readonly JobPositionMapper  $mapper,
    ) {
        parent::__construct($repository, $transformer);
    }

    public function store(StoreJobPositionRequest $request): JsonResponse
    {
        $dto = $this->mapper->fromRequest($request);

        $model = $this->service->create($dto);

        return $this->response->created(
            $this->transformer->transformOne($model)
        );
    }

    // PUT/PATCH /job-positions/{id}
    public function update(UpdateJobPositionRequest $request, int $id): JsonResponse
    {
        $jobPosition = $this->repository->findOrFail($id);
        $dto         = $this->mapper->fromRequestForUpdate($request, $jobPosition);

        $updated = $this->service->update($jobPosition, $dto);

        return $this->response->success(
            $this->transformer->transformOne($updated)
        );
    }

    // DELETE /job-positions/{id}
    public function destroy(int $id): JsonResponse
    {
        $jobPosition = $this->repository->findOrFail($id);
        $this->service->delete($jobPosition);

        return $this->response->success([]);
    }
}
