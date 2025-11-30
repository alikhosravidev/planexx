<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Controllers\API\V1\Admin;

use App\Contracts\Controller\APIBaseController;
use App\Core\Organization\Http\Requests\V1\Admin\StoreAddressRequest;
use App\Core\Organization\Http\Requests\V1\Admin\UpdateAddressRequest;
use App\Core\Organization\Http\Transformers\V1\Admin\AddressTransformer;
use App\Core\Organization\Mappers\AddressMapper;
use App\Core\Organization\Repositories\AddressRepository;
use App\Core\Organization\Services\AddressService;
use Illuminate\Http\JsonResponse;

class AddressController extends APIBaseController
{
    public function __construct(
        AddressRepository               $repository,
        AddressTransformer              $transformer,
        private readonly AddressService $service,
        private readonly AddressMapper  $mapper,
    ) {
        parent::__construct($repository, $transformer);
    }

    // POST /location/addresses
    public function store(StoreAddressRequest $request): JsonResponse
    {
        $dto = $this->mapper->fromRequest($request);

        $model = $this->service->create($dto);

        return $this->response->created(
            $this->transformer->transformOne($model)
        );
    }

    // PUT/PATCH /location/addresses/{id}
    public function update(UpdateAddressRequest $request, int $id): JsonResponse
    {
        $address = $this->repository->findOrFail($id);
        $dto     = $this->mapper->fromRequestForUpdate($request, $address);

        $updated = $this->service->update($address, $dto);

        return $this->response->success(
            $this->transformer->transformOne($updated)
        );
    }

    // DELETE /location/addresses/{id}
    public function destroy(int $id): JsonResponse
    {
        $address = $this->repository->findOrFail($id);
        $this->service->delete($address);

        return $this->response->success([]);
    }
}
