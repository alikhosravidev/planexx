<?php

declare(strict_types=1);

namespace Modules\Product\Services\Product\Actions;

use Modules\Product\DTOs\ProductDTO;
use Modules\Product\Enums\ProductStatusEnum;

/**
 * Resolves the final product status. Prevents publishing
 * when mandatory fields (title, price > 0) are not met.
 */
final class ResolveProductStatusAction
{
    public function execute(ProductDTO $dto): ProductStatusEnum
    {
        if ($dto->status === ProductStatusEnum::Active && ! $this->canPublish($dto)) {
            return ProductStatusEnum::Draft;
        }

        return $dto->status;
    }

    private function canPublish(ProductDTO $dto): bool
    {
        return $dto->title !== '' && $dto->price > 0;
    }
}
