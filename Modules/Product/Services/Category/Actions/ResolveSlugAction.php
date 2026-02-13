<?php

declare(strict_types=1);

namespace Modules\Product\Services\Category\Actions;

use Illuminate\Support\Str;
use Modules\Product\DTOs\CategoryDTO;
use Modules\Product\Entities\Category;

/**
 * Resolves slug for a category: uses provided slug,
 * falls back to existing, or auto-generates from name.
 */
final class ResolveSlugAction
{
    public function execute(CategoryDTO $dto, ?Category $existing = null): string
    {
        if (! empty($dto->slug)) {
            return $dto->slug;
        }

        if ($existing !== null && $existing->slug !== null) {
            return $existing->slug;
        }

        return Str::slug($dto->name);
    }
}
