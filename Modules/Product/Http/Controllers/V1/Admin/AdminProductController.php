<?php

declare(strict_types=1);

namespace Modules\Product\Http\Controllers\V1\Admin;

use App\Contracts\Controller\BaseAPIController;
use Illuminate\Http\JsonResponse;
use Modules\Product\Entities\Product;
use Modules\Product\Http\Requests\V1\Admin\StoreProductRequest;
use Modules\Product\Http\Requests\V1\Admin\UpdateProductRequest;
use Modules\Product\Http\Transformers\V1\Admin\ProductTransformer;
use Modules\Product\Mappers\ProductMapper;
use Modules\Product\Repositories\ProductRepository;
use Modules\Product\Services\ProductService;

class AdminProductController extends BaseAPIController
{
    public function __construct(
        ProductRepository                    $repository,
        ProductTransformer                   $transformer,
        private readonly ProductService      $service,
        private readonly ProductMapper       $mapper,
    ) {
        parent::__construct($repository, $transformer);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $dto     = $this->mapper->fromRequest($request);
        $product = $this->service->create($dto, $request->user()->id);

        return $this->response->created(
            $this->transformer->transformOne($product),
            'محصول جدید با موفقیت ایجاد شد.',
        );
    }

    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        /** @var Product $product */
        $product = $this->repository->findOrFail($id);

        $dto     = $this->mapper->fromRequestForUpdate($request, $product);
        $updated = $this->service->update($product, $dto, $request->user()->id);

        return $this->response->success(
            $this->transformer->transformOne($updated),
            'محصول با موفقیت بروزرسانی شد.',
        );
    }

    public function destroy(int $id): JsonResponse
    {
        /** @var Product $product */
        $product = $this->repository->findOrFail($id);
        $this->service->delete($product);

        return $this->response->success([], 'محصول مورد نظر حذف شد.');
    }
}
