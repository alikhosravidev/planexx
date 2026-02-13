<?php

declare(strict_types=1);

namespace Modules\Product\Registrars;

use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryManagerInterface;
use App\Services\Stats\StatBuilder;
use Modules\Product\Enums\ProductStatusEnum;
use Modules\Product\Repositories\CustomListItemRepository;
use Modules\Product\Repositories\CustomListRepository;
use Modules\Product\Repositories\ProductRepository;

class ProductStatsRegistrar implements RegistrarInterface
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly CustomListRepository $customListRepository,
        private readonly CustomListItemRepository $customListItemRepository,
    ) {
    }

    public function register(RegistryManagerInterface $manager): void
    {
        $manager->register('product.dashboard.stats', function (StatBuilder $builder) {
            $productsCount       = $this->productRepository->count();
            $activeProductsCount = $this->productRepository->newQuery()
                ->where('status', '=', ProductStatusEnum::Active)
                ->count();
            $listCount      = $this->customListRepository->count();
            $listItemsCount = $this->customListItemRepository->count();

            $builder->stat('کل محصولات', 'products-count')
                ->value($productsCount)
                ->icon('fa-solid fa-box')
                ->color('blue')
                ->order(1);

            $builder->stat('محصولات فعال', 'active-products')
                ->value($activeProductsCount)
                ->icon('fa-solid fa-check-circle')
                ->color('green')
                ->order(2);

            $builder->stat('تعداد لیست‌ها', 'lists-count')
                ->value($listCount)
                ->icon('fa-solid fa-clipboard-list')
                ->color('purple')
                ->order(3);

            $builder->stat('آیتم‌های لیست‌ها', 'list-items-count')
                ->value($listItemsCount)
                ->icon('fa-solid fa-list-check')
                ->color('orange')
                ->order(4);
        });
    }
}
