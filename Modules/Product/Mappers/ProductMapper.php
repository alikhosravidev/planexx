<?php

declare(strict_types=1);

namespace Modules\Product\Mappers;

use App\Utilities\OrNull;
use App\ValueObjects\Price;
use Illuminate\Http\Request;
use Modules\Product\Collections\CategoryIdCollection;
use Modules\Product\DTOs\ProductDTO;
use Modules\Product\Entities\Product;
use Modules\Product\Enums\ProductStatusEnum;
use Modules\Product\ValueObjects\CategoryId;

class ProductMapper
{
    public function fromRequest(Request $request): ProductDTO
    {
        $salePriceValue = OrNull::intOrNull($request->input('sale_price'));

        return new ProductDTO(
            title: $request->input('title'),
            price: new Price((int) $request->input('price')),
            slug: OrNull::stringOrNull($request->input('slug')),
            salePrice: $salePriceValue !== null ? new Price($salePriceValue) : null,
            status: $request->filled('status')
                ? ProductStatusEnum::from((int) $request->input('status'))
                : ProductStatusEnum::Draft,
            isFeatured: $request->boolean('is_featured', false),
            categoryIds: $this->mapCategoryIds($request->input('category_ids', [])),
        );
    }

    public function fromRequestForUpdate(Request $request, Product $product): ProductDTO
    {
        $salePriceValue = $request->has('sale_price')
            ? OrNull::intOrNull($request->input('sale_price'))
            : $product->sale_price;

        return new ProductDTO(
            title: $request->input('title', $product->title),
            price: new Price((int) $request->input('price', $product->price)),
            slug: OrNull::stringOrNull($request->input('slug')) ?? $product->slug,
            salePrice: $salePriceValue !== null ? new Price($salePriceValue) : null,
            status: $request->filled('status')
                ? ProductStatusEnum::from((int) $request->input('status'))
                : $product->status,
            isFeatured: $request->has('is_featured')
                ? $request->boolean('is_featured')
                : $product->is_featured,
            categoryIds: $request->has('category_ids')
                ? $this->mapCategoryIds($request->input('category_ids', []))
                : $this->mapCategoryIds($product->categories->pluck('id')->toArray()),
        );
    }

    /**
     * @param  array<int>  $categoryIds
     */
    private function mapCategoryIds(array $categoryIds): CategoryIdCollection
    {
        $ids = array_map(
            fn (int $id) => new CategoryId($id),
            $categoryIds,
        );

        return new CategoryIdCollection($ids);
    }
}
