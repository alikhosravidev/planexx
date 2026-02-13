<?php

declare(strict_types=1);

namespace Modules\Product\Services\CustomList\Actions;

use Illuminate\Support\Str;
use Modules\Product\DTOs\CustomListDTO;
use Modules\Product\Entities\CustomList;

/**
 * Resolves slug for a custom list: uses provided slug,
 * falls back to existing, or auto-generates from title.
 */
final class ResolveSlugAction
{
    public function execute(CustomListDTO $dto, ?CustomList $existing = null): string
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
