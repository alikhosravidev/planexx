<?php

declare(strict_types=1);

namespace App\Registrars;

use App\Contracts\MenuRegistrar;
use App\Services\Menu\MenuBuilder;
use App\Services\Menu\MenuManager;

class CoreDashboardMenuRegistrar implements MenuRegistrar
{
    public function register(MenuManager $menu): void
    {
        $menu->register('dashboard.sidebar', function (MenuBuilder $menu) {
            $menu->item('داشبورد', 'dashboard')
                ->route('dashboard')
                ->icon('fa-solid fa-chart-line')
                ->order(1);
        });
    }
}
