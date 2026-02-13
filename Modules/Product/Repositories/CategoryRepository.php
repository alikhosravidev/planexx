<?php

declare(strict_types=1);

namespace Modules\Product\Repositories;

use App\Contracts\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Modules\Product\Entities\Category;

class CategoryRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id'   => '=',
        'name' => 'like',
        'slug' => 'like',
    ];

    public array $filterableFields = [
        'parent_id' => '=',
        'is_active' => '=',
    ];

    public array $sortableFields = [
        'id',
        'name',
        'sort_order',
        'created_at',
    ];

    public function model(): string
    {
        return Category::class;
    }

    public function getRootCategories(): Collection
    {
        return $this->makeModel()
            ->newQuery()
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();
    }

    public function getChildrenOf(int $parentId): Collection
    {
        return $this->makeModel()
            ->newQuery()
            ->where('parent_id', $parentId)
            ->orderBy('sort_order')
            ->get();
    }

    public function hasChildren(int $categoryId): bool
    {
        return $this->makeModel()
            ->newQuery()
            ->where('parent_id', $categoryId)
            ->exists();
    }
}
