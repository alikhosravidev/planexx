<?php

declare(strict_types=1);

namespace Modules\Product\Registrars;

use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryManagerInterface;
use App\Services\Stats\StatBuilder;
use Modules\Product\Entities\Category;
use Modules\Product\Repositories\CategoryRepository;

class CategoryStatsRegistrar implements RegistrarInterface
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
    ) {
    }

    public function register(RegistryManagerInterface $manager): void
    {
        $manager->register('product.category.stats', function (StatBuilder $builder) {
            $stats = $this->categoryRepository
                ->newQuery()
                ->selectRaw('COUNT(DISTINCT ' . Category::TABLE . '.id) as total_categories')
                ->selectRaw('COUNT(DISTINCT CASE WHEN is_active = 1 THEN ' . Category::TABLE . '.id END) as active_categories')
                ->selectRaw('COUNT(DISTINCT CASE WHEN parent_id IS NULL THEN ' . Category::TABLE . '.id END) as root_categories')
                ->selectRaw('(SELECT COUNT(*) FROM ' . Category::PIVOT_TABLE . ') as products_categorized')
                ->first();

            $builder->stat('کل دسته‌بندی‌ها', 'product-total-categories')
                ->value($stats->total_categories ?? 0)
                ->icon('fa-solid fa-folder-tree')
                ->color('blue')
                ->order(1);

            $builder->stat('دسته‌بندی فعال', 'product-active-categories')
                ->value($stats->active_categories ?? 0)
                ->icon('fa-solid fa-check-circle')
                ->color('green')
                ->order(2);

            $builder->stat('دسته‌بندی اصلی', 'product-root-categories')
                ->value($stats->root_categories ?? 0)
                ->icon('fa-solid fa-sitemap')
                ->color('purple')
                ->order(3);

            $builder->stat('محصولات دسته‌بندی شده', 'product-categorized-products')
                ->value($stats->products_categorized ?? 0)
                ->icon('fa-solid fa-tag')
                ->color('orange')
                ->order(4);
        });
    }
}
