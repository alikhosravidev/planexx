<?php

declare(strict_types=1);

namespace Modules\Product\Registrars;

use App\Contracts\MenuRegistrar;
use App\Services\Menu\MenuBuilder;
use App\Services\Menu\MenuManager;

class ProductMenuRegistrar implements MenuRegistrar
{
    public function register(MenuManager $menu): void
    {
        $menu->register('dashboard.sidebar', function (MenuBuilder $menu) {
            $menu->item(trans('Product::menus.product'), 'product')
                ->route('web.product.dashboard')
                ->icon('fa-solid fa-boxes-stacked')
                ->order(4);
        });

        $menu->register('product.sidebar', function (MenuBuilder $menu) {
            $menu->item('داشبورد ماژول', 'product-dashboard')
                ->route('web.product.dashboard')
                ->icon('fa-solid fa-box')
                ->order(1);

            $menu->item('محصولات', 'products-list')
                ->route('web.product.products.index')
                ->icon('fa-solid fa-box')
                ->order(2);

            $menu->item('دسته‌بندی محصولات', 'product-categories')
                ->route('web.product.categories.index')
                ->icon('fa-solid fa-folder-tree')
                ->order(3);

            $menu->item('لیست‌ها', 'lists-index')
                ->route('web.product.custom-lists.index')
                ->icon('fa-solid fa-clipboard-list')
                ->order(4);
        });
    }
}
