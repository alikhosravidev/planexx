<?php

declare(strict_types=1);

namespace Modules\Product\Services;

use Illuminate\Support\Facades\DB;
use Modules\Product\DTOs\ProductDTO;
use Modules\Product\Entities\Product;
use Modules\Product\Exceptions\ProductException;
use Modules\Product\Repositories\ProductRepository;
use Modules\Product\Services\Product\Actions\ResolveProductStatusAction;
use Modules\Product\Services\Product\Actions\ResolveSlugAction;
use Modules\Product\Services\Product\Actions\SyncProductCategoriesAction;
use Modules\Product\Services\Product\Validation\DeletionValidator;
use Modules\Product\Services\Product\Validation\SalePriceValidator;

readonly class ProductService
{
    public function __construct(
        private ProductRepository $productRepository,
        private SalePriceValidator $salePriceValidator,
        private DeletionValidator $deletionValidator,
        private ResolveProductStatusAction $resolveStatus,
        private SyncProductCategoriesAction $syncCategories,
        private ResolveSlugAction $resolveSlug,
    ) {
    }

    /**
     * @throws ProductException|\Throwable
     */
    public function create(ProductDTO $dto, int $createdBy): Product
    {
        return DB::transaction(function () use ($dto, $createdBy) {
            $this->salePriceValidator->validate($dto->price, $dto->salePrice);
            dd($dto->categoryIds);
            $data               = $dto->toArray();
            $data['slug']       = $this->resolveSlug->execute($dto);
            $data['status']     = $this->resolveStatus->execute($dto)->value;
            $data['created_by'] = $createdBy;

            $product = $this->productRepository->create($data);

            if ($dto->categoryIds->isNotEmpty()) {
                $this->syncCategories->execute($product, $dto->categoryIds);
            }

            return $product->load('categories');
        });
    }

    /**
     * @throws ProductException|\Throwable
     */
    public function update(Product $product, ProductDTO $dto, int $updatedBy): Product
    {
        return DB::transaction(function () use ($product, $dto, $updatedBy) {
            $this->salePriceValidator->validate($dto->price, $dto->salePrice);

            $data               = $dto->toArray();
            $data['slug']       = $this->resolveSlug->execute($dto, $product);
            $data['status']     = $this->resolveStatus->execute($dto)->value;
            $data['updated_by'] = $updatedBy;

            $product = $this->productRepository->update($product->id, $data);

            if ($dto->categoryIds->isNotEmpty()) {
                $this->syncCategories->execute($product, $dto->categoryIds);
            }

            return $product->load('categories');
        });
    }

    /**
     * @throws ProductException
     */
    public function delete(Product $product): bool
    {
        $this->deletionValidator->validate($product);

        return $this->productRepository->delete($product->id);
    }
}
