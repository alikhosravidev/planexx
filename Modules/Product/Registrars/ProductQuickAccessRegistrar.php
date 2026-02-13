<?php

declare(strict_types=1);

namespace Modules\Product\Registrars;

use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryManagerInterface;
use App\Services\QuickAccess\QuickAccessBuilder;

class ProductQuickAccessRegistrar implements RegistrarInterface
{
    public function register(RegistryManagerInterface $manager): void
    {
        $manager->register('dashboard.quick-access', function (QuickAccessBuilder $builder) {
            $builder->item(trans('Product::menus.product'), 'product')
                ->route('web.product.dashboard')
                ->icon('fa-solid fa-boxes-stacked')
                ->color('stone')
                ->enabled()
                ->order(7);
        });

        $manager->register('product.dashboard.quick-access', function (QuickAccessBuilder $builder) {
            $builder->item('مدیریت محصولات', 'bpms-workflows')
                ->route('web.product.products.index')
                ->icon('fa-solid fa-box')
                ->color('blue')
                ->enabled()
                ->order(1);

            $builder->item('افزودن محصول جدید', 'bpms-workflows-create')
                ->route('web.product.products.create')
                ->icon('fa-solid fa-plus-circle')
                ->color('green')
                ->enabled()
                ->order(2);

            $builder->item('مدیریت لیست‌ها', 'bpms-tasks-active')
                ->route('web.product.custom-lists.index')
                ->icon('fa-solid fa-clipboard-list')
                ->color('purple')
                ->order(3);

            $builder->item('لیست دسته‌بندی محصولات', 'bpms-reports')
                ->route('web.product.categories.index')
                ->icon('fa-solid fa-folder-plus')
                ->color('orange')
                ->order(4);
        });

    }
}
