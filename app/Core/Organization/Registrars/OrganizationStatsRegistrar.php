<?php

declare(strict_types=1);

namespace App\Core\Organization\Registrars;

use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryManagerInterface;
use App\Domains\User\UserQuery;
use App\Services\Stats\StatBuilder;

class OrganizationStatsRegistrar implements RegistrarInterface
{
    public function __construct(
        private readonly UserQuery $userQuery,
    ) {
    }

    public function register(RegistryManagerInterface $manager): void
    {
        $manager->register('org.dashboard.stats', function (StatBuilder $builder) {
            $stats = $this->userQuery->getActiveUserStats();

            $builder->stat('تعداد کاربران', 'org-total-users')
                ->value($stats->usersCount)
                ->icon('fa-solid fa-users')
                ->color('blue')
                ->order(1);

            $builder->stat('کارمندان فعال', 'org-active-employees')
                ->value($stats->employeesCount)
                ->icon('fa-solid fa-user-tie')
                ->color('green')
                ->order(2);

            $builder->stat('دپارتمان‌ها', 'org-active-customers')
                ->value(0)
                ->icon('fa-solid fa-sitemap')
                ->color('purple')
                ->order(3);

            $builder->stat('موقعیت‌های شغلی', 'org-active-customers')
                ->value(0)
                ->icon('fa-solid fa-briefcase')
                ->color('orange')
                ->order(4);
        });
    }
}
