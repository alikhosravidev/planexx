<?php

declare(strict_types=1);

namespace Modules\Product\Exceptions;

use App\Exceptions\BusinessException;

final class ProductException extends BusinessException
{
    public static function salePriceExceedsPrice(): self
    {
        return new self(trans('Product::errors.sale_price_exceeds_price'));
    }

    public static function cannotDeleteActiveProduct(): self
    {
        return new self(trans('Product::errors.cannot_delete_active_product'));
    }
}
