<?php

declare(strict_types=1);

namespace App\Registrars;

use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryManagerInterface;
use App\Services\QuickAccess\QuickAccessBuilder;

class DashboardQuickAccessRegistrar implements RegistrarInterface
{
    public function register(RegistryManagerInterface $manager): void
    {
        $manager->register('dashboard.quick-access', function (QuickAccessBuilder $builder) {
            $builder->item('ساختار سازمانی', 'organization')
                ->route('org.dashboard')
                ->icon('fa-solid fa-sitemap')
                ->color('blue')
                ->enabled(true)
                ->order(1);

            $builder->item('مدیریت کاربران', 'users')
                ->route('org.users.index', ['type' => 'employee'])
                ->icon('fa-solid fa-user-tie')
                ->color('green')
                ->enabled(true)
                ->order(2);

            $builder->item('دپارتمان‌ها', 'departments')
                ->url('/dashboard/org/departments/list.php')
                ->icon('fa-solid fa-building')
                ->color('purple')
                ->enabled(true)
                ->order(3);

            $builder->item('گزارشات', 'reports')
                ->url('#')
                ->icon('fa-solid fa-chart-bar')
                ->color('orange')
                ->enabled(false)
                ->order(4);

            $builder->item('تنظیمات', 'settings')
                ->url('#')
                ->icon('fa-solid fa-cog')
                ->color('gray')
                ->enabled(false)
                ->order(5);

            $builder->item('پشتیبانی', 'support')
                ->url('#')
                ->icon('fa-solid fa-headset')
                ->color('teal')
                ->enabled(false)
                ->order(6);
        });
    }
}
