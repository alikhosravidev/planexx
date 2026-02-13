<?php

declare(strict_types=1);

namespace Modules\Product\Services\Product\Actions;

use Illuminate\Support\Str;
use Modules\Product\DTOs\ProductDTO;
use Modules\Product\Entities\Product;

/**
 * Resolves slug for a product: uses provided slug,
 * falls back to existing, or auto-generates from title.
 */
final class ResolveSlugAction
{
    public function execute(ProductDTO $dto, ?Product $existing = null): string
    {
        if ($dto->slug !== null && $dto->slug !== '') {
            return $dto->slug;
        }

        if ($existing !== null && $existing->slug !== null) {
            return $existing->slug;
        }

        return Str::slug($dto->title);
    }
}
