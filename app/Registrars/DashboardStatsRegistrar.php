<?php

declare(strict_types=1);

namespace App\Registrars;

use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryManagerInterface;
use App\Domains\User\UserQuery;
use App\Services\Stats\StatBuilder;

class DashboardStatsRegistrar implements RegistrarInterface
{
    public function __construct(
        private readonly UserQuery $userQuery,
    ) {
    }

    public function register(RegistryManagerInterface $manager): void
    {
        $manager->register('dashboard.stats', function (StatBuilder $builder) {
            $stats = $this->userQuery->getActiveUserStats();

            $builder->stat('کاربران', 'total-users')
                ->value($stats->usersCount)
                ->icon('fa-solid fa-users')
                ->color('blue')
                ->order(1);

            $builder->stat('کارکنان', 'active-users')
                ->value($stats->employeesCount)
                ->icon('fa-solid fa-user-tie')
                ->color('green')
                ->order(2);

            $builder->stat('تجربه‌ها', 'active-users')
                ->value(0)
                ->icon('fa-solid fa-lightbulb')
                ->color('blue')
                ->order(3);

            $builder->stat('مشتریان', 'active-users')
                ->value($stats->customersCount)
                ->icon('fa-solid fa-handshake')
                ->color('orange')
                ->order(4);

        });
    }
}
