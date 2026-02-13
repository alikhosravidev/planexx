<?php

declare(strict_types=1);

namespace Modules\Product\Services\Product\Validation;

use App\ValueObjects\Price;
use Modules\Product\Exceptions\ProductException;

/**
 * Validates that the sale price (if provided) is strictly
 * less than the base price.
 */
final class SalePriceValidator
{
    /**
     * @throws ProductException
     */
    public function validate(Price $price, ?Price $salePrice): void
    {
        if ($salePrice === null) {
            return;
        }

        if ($salePrice->isGreaterThan($price) || $salePrice->equals($price)) {
            throw ProductException::salePriceExceedsPrice();
        }
    }
}
