<?php

declare(strict_types=1);

namespace Modules\Product\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\Organization\Http\Transformers\V1\Admin\UserTransformer;
use App\Services\Transformer\FieldTransformers\EnumTransformer;
use App\Services\Transformer\FieldTransformers\PriceTransformer;
use Modules\Product\Entities\Product;

class ProductTransformer extends BaseTransformer
{
    protected array $fieldTransformers = [
        'status'     => EnumTransformer::class,
        'sale_price' => PriceTransformer::class,
    ];

    protected array $availableIncludes = [
        'categories',
        'creator',
        'updater',
    ];

    protected function getVirtualFieldResolvers(): array
    {
        return [
            'has_discount' => fn (Product $product) => $product->sale_price !== null && $product->sale_price < $product->price,
        ];
    }

    public function includeCategories(Product $product)
    {
        return $this->collectionRelation(
            model: $product,
            relationName: 'categories',
            transformer: CategoryTransformer::class,
        );
    }

    public function includeCreator(Product $product)
    {
        return $this->itemRelation(
            model: $product,
            relationName: 'creator',
            transformer: UserTransformer::class,
            foreignKey: 'created_by',
        );
    }

    public function includeUpdater(Product $product)
    {
        return $this->itemRelation(
            model: $product,
            relationName: 'updater',
            transformer: UserTransformer::class,
            foreignKey: 'updated_by',
        );
    }
}
