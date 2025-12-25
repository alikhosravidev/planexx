<?php

declare(strict_types=1);

namespace App\Core\BPMS\Registrars;

use App\Contracts\MenuRegistrar;
use App\Services\Menu\MenuBuilder;
use App\Services\Menu\MenuManager;

class BPMSMenuRegistrar implements MenuRegistrar
{
    public function register(MenuManager $menu): void
    {
        $menu->register('dashboard.sidebar', function (MenuBuilder $menu) {
            $menu->item('مدیریت وظایف', 'bpms')
                ->route('web.bpms.dashboard')
                ->icon('fa-solid fa-list-check')
                ->order(3);
        });

        $menu->register('bpms.sidebar', function (MenuBuilder $menu) {
            $menu->item('داشبورد ماژول', 'bpms-dashboard')
                ->route('web.bpms.dashboard')
                ->icon('fa-solid fa-chart-pie')
                ->order(1);

            $menu->item('مدیریت فرایندها', 'bpms-workflows')
                ->route('web.bpms.workflows.index')
                ->icon('fa-solid fa-diagram-project')
                ->order(2);

            $menu->item('کارها و وظایف', 'bpms-tasks')
                ->route('web.bpms.tasks.index')
                ->icon('fa-solid fa-list-check')
                ->order(3);

            $menu->item('گزارشات', 'bpms-reports')
                ->route('#')
                ->icon('fa-solid fa-chart-bar')
                ->order(4);
        });
    }
}
