<?php

declare(strict_types=1);

namespace App\Registrars;

use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryManagerInterface;
use App\Core\Organization\Entities\Department;
use App\Core\Organization\Entities\User;
use App\Services\Stats\StatBuilder;

class DashboardStatsRegistrar implements RegistrarInterface
{
    public function register(RegistryManagerInterface $manager): void
    {
        $manager->register('dashboard.stats', function (StatBuilder $builder) {
            $builder->stat('کل کاربران', 'total-users')
                ->value(fn () => User::count())
                ->icon('fa-solid fa-users')
                ->color('blue')
                ->order(1);

            $builder->stat('کاربران فعال', 'active-users')
                ->value(fn () => User::where('is_active', true)->count())
                ->icon('fa-solid fa-user-check')
                ->color('green')
                ->order(2);

            $builder->stat('دپارتمان‌ها', 'departments')
                ->value(fn () => Department::count())
                ->icon('fa-solid fa-sitemap')
                ->color('purple')
                ->order(3);

            //$builder->stat('وظایف امروز', 'today-tasks')
            //    ->value(fn () => Task::whereDate('created_at', today())->count())
            //    ->icon('fa-solid fa-tasks')
            //    ->color('orange')
            //    ->order(4);
        });
    }
}
