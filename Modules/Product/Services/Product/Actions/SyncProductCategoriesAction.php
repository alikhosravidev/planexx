<?php

declare(strict_types=1);

namespace Modules\Product\Services\Product\Actions;

use Modules\Product\Collections\CategoryIdCollection;
use Modules\Product\Entities\Product;

/**
 * Synchronizes the many-to-many relationship between
 * a product and its categories.
 */
final class SyncProductCategoriesAction
{
    public function execute(Product $product, CategoryIdCollection $categoryIds): void
    {
        $product->categories()->sync($categoryIds->toIds());
    }
}
