<?php

declare(strict_types=1);

namespace Modules\Product\Services\Category\Actions;

use Modules\Product\Collections\CategoryIdCollection;
use Modules\Product\Repositories\CategoryRepository;
use Modules\Product\ValueObjects\CategoryId;

/**
 * Recursively collects all descendant IDs of a given category.
 * Used for circular reference detection in hierarchical trees.
 */
final class CollectDescendantIdsAction
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
    ) {
    }

    public function execute(CategoryId $categoryId): CategoryIdCollection
    {
        $descendants = new CategoryIdCollection();
        $children    = $this->categoryRepository->getChildrenOf($categoryId->value);

        foreach ($children as $child) {
            $childId = new CategoryId($child->id);
            $descendants->push($childId);
            $descendants = $descendants->merge($this->execute($childId));
        }

        return $descendants;
    }
}
