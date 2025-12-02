<?php

declare(strict_types=1);

namespace App\Core\Organization\Registrars;

use App\Contracts\MenuRegistrar;
use App\Services\Menu\MenuBuilder;
use App\Services\Menu\MenuManager;

class OrganizationMenuRegistrar implements MenuRegistrar
{
    public function register(MenuManager $menu): void
    {
        $menu->register('dashboard.sidebar', function (MenuBuilder $menu) {
            $menu->item('ساختار سازمانی', 'org')
                ->route('web.org.dashboard')
                ->icon('fa-solid fa-sitemap')
                ->order(2);
        });

        $menu->register('org.sidebar', function (MenuBuilder $menu) {
            $menu->item('داشبورد ماژول', 'org-dashboard')
                ->route('web.org.dashboard')
                ->icon('fa-solid fa-chart-pie')
                ->order(1);

            $menu->item('کارکنان', 'org-employee')
                ->route('web.org.users.index', ['user_type' => 'employee'])
                ->icon('fa-solid fa-user-tie')
                ->order(2);

            $menu->item('مشتریان', 'org-customer')
                ->route('web.org.users.index', ['user_type' => 'customer'])
                ->icon('fa-solid fa-users')
                ->order(3);

            $menu->item('کاربران عادی', 'org-user')
                ->route('web.org.users.index', ['user_type' => 'user'])
                ->icon('fa-solid fa-user')
                ->order(4);

            $menu->item('دپارتمان‌ها', 'org-departments')
                ->route('web.org.departments.index')
                ->icon('fa-solid fa-sitemap')
                ->order(5);

            $menu->item('نقش‌ها و دسترسی‌ها', 'org-roles')
                ->route('web.org.roles.index')
                ->icon('fa-solid fa-shield-halved')
                ->order(6);
        });
    }
}
