<?php

declare(strict_types=1);

namespace Modules\Product\Services;

use Modules\Product\DTOs\CategoryDTO;
use Modules\Product\Entities\Category;
use Modules\Product\Exceptions\CategoryException;
use Modules\Product\Repositories\CategoryRepository;
use Modules\Product\Services\Category\Actions\CollectDescendantIdsAction;
use Modules\Product\Services\Category\Actions\ResolveSlugAction;
use Modules\Product\Services\Category\Validation\CircularReferenceValidator;
use Modules\Product\ValueObjects\CategoryId;

readonly class CategoryService
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private CircularReferenceValidator $circularReferenceValidator,
        private CollectDescendantIdsAction $collectDescendantIds,
        private ResolveSlugAction $resolveSlug,
    ) {
    }

    public function create(CategoryDTO $dto): Category
    {
        $data         = $dto->toArray();
        $data['slug'] = $this->resolveSlug->execute($dto);

        return $this->categoryRepository->create($data);
    }

    /**
     * @throws CategoryException
     */
    public function update(Category $category, CategoryDTO $dto): Category
    {
        if ($dto->parentId !== null) {
            $categoryId    = new CategoryId($category->id);
            $descendantIds = $this->collectDescendantIds->execute($categoryId);
            $this->circularReferenceValidator->validate($categoryId, $dto->parentId, $descendantIds);
        }

        $data         = $dto->toArray();
        $data['slug'] = $this->resolveSlug->execute($dto, $category);

        return $this->categoryRepository->update($category->id, $data);
    }

    /**
     * @throws CategoryException
     */
    public function delete(Category $category): bool
    {
        $hasChildren   = $this->categoryRepository->hasChildren($category->id);
        $productsCount = $category->products()->count();

        if ($hasChildren) {
            throw CategoryException::hasChildren();
        }

        if ($productsCount > 0) {
            throw CategoryException::hasProducts();
        }

        return $this->categoryRepository->delete($category->id);
    }
}
