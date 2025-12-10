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
            $builder->stat('تجربه‌ها', 'active-users')
                ->value(0)
                ->icon('fa-solid fa-lightbulb')
                ->color('blue')
                ->order(3);
        });
    }
}
