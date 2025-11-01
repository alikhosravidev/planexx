<?php

namespace App\Core\User\Http\Controllers\V1\Admin;

use App\Contracts\Controller\BaseController;
use App\Core\Location\Http\Transformers\V3\Admin\AddressTransformer;
use App\Core\User\Http\Requests\V1\Admin\CreateAddressRequest;
use App\Core\User\Http\Requests\V1\Admin\UpdateAddressRequest;
use App\Core\User\Repositories\AddressRepository;
use App\Core\User\Services\AddressService;
use App\Core\User\Services\Mappers\AddressMapper;
use Illuminate\Http\JsonResponse;

class AddressController extends BaseController
{
    public function __construct(
        AddressRepository $repository,
        AddressTransformer $transformer,
        private readonly AddressService $service,
        private readonly AddressMapper $mapper,
    ) {
        parent::__construct($repository, $transformer);
    }

    public function store(CreateAddressRequest $request): JsonResponse
    {
        $dto = $this->mapper->fromRequest($request);

        $model = $this->service->createAddress($dto);

        return $this->response->created(
            $this->transformer->transformOne($model)
        );
    }
    // GET /location/addresses/{id}

    // PUT/PATCH /location/addresses/{id}
    public function update(UpdateAddressRequest $request, int $id): JsonResponse
    {
        $address = $this->repository->findOrFail($id);
        $dto = $this->mapper->fromRequestForUpdate($request, $address);

        $updated = $this->service->updateAddress($address, $dto);

        return $this->response->success(
            $this->transformer->transformOne($updated)
        );
    }

    // DELETE /location/addresses/{id}
    public function destroy(int $id): JsonResponse
    {
        $address = $this->repository->findOrFail($id);
        $this->service->deleteAddress($address);

        return $this->response->success([]);
    }
}
