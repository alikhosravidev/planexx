<?php

declare(strict_types=1);

namespace Modules\Product\Exceptions;

use App\Exceptions\BusinessException;

final class CategoryException extends BusinessException
{
    public static function circularReference(): self
    {
        return new self('ارجاع دایره‌ای: دسته‌بندی نمی‌تواند والد خود یا فرزندان خود باشد.');
    }

    public static function hasChildren(): self
    {
        return new self('دسته‌بندی دارای زیردسته‌بندی است و قابل حذف نیست.');
    }

    public static function hasProducts(): self
    {
        return new self('دسته‌بندی دارای محصول مرتبط است و قابل حذف نیست.');
    }
}
