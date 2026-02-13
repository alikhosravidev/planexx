<?php

declare(strict_types=1);

namespace Modules\Product\Services\Product\Validation;

use Modules\Product\Entities\Product;
use Modules\Product\Enums\ProductStatusEnum;
use Modules\Product\Exceptions\ProductException;

/**
 * Validates that a product is not active before allowing deletion.
 */
final class DeletionValidator
{
    /**
     * @throws ProductException
     */
    public function validate(Product $product): void
    {
        if ($product->status === ProductStatusEnum::Active) {
            throw ProductException::cannotDeleteActiveProduct();
        }
    }
}
