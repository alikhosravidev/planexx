<?php

declare(strict_types=1);

namespace Modules\Product\Services\Category\Validation;

use Modules\Product\Collections\CategoryIdCollection;
use Modules\Product\Exceptions\CategoryException;
use Modules\Product\ValueObjects\CategoryId;

/**
 * Validates that a category is not being set as a child of itself
 * or any of its own descendants (prevents circular reference in tree).
 */
final class CircularReferenceValidator
{
    /**
     * @throws CategoryException
     */
    public function validate(
        CategoryId $categoryId,
        ?CategoryId $newParentId,
        CategoryIdCollection $descendantIds,
    ): void {
        if ($newParentId === null) {
            return;
        }

        if ($newParentId->equals($categoryId) || $descendantIds->contains($newParentId)) {
            throw CategoryException::circularReference();
        }
    }
}
