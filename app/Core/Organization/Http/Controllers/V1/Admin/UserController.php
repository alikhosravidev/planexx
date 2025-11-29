<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Controllers\V1\Admin;

use App\Contracts\Controller\BaseController;
use App\Core\Organization\Http\Requests\V1\Admin\StoreAddressRequest;
use App\Core\Organization\Http\Requests\V1\Admin\UpdateAddressRequest;
use App\Core\Organization\Http\Transformers\V1\Admin\UserTransformer;
use App\Core\Organization\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;

class UserController extends BaseController
{
    public function __construct(
        UserRepository               $repository,
        UserTransformer              $transformer,
    ) {
        parent::__construct($repository, $transformer);
    }

    public function store(StoreAddressRequest $request): JsonResponse
    {
        $dto = $this->mapper->fromRequest($request);

        $model = $this->service->create($dto);

        return $this->response->created(
            $this->transformer->transformOne($model)
        );
    }

    public function update(UpdateAddressRequest $request, int $id): JsonResponse
    {
        $address = $this->repository->findOrFail($id);
        $dto     = $this->mapper->fromRequestForUpdate($request, $address);

        $updated = $this->service->update($address, $dto);

        return $this->response->success(
            $this->transformer->transformOne($updated)
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $address = $this->repository->findOrFail($id);
        $this->service->delete($address);

        return $this->response->success([]);
    }
}
