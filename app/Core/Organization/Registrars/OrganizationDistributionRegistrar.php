<?php

declare(strict_types=1);

namespace App\Core\Organization\Registrars;

use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryManagerInterface;
use App\Domains\User\UserQuery;
use App\Services\Distribution\DistributionBuilder;

class OrganizationDistributionRegistrar implements RegistrarInterface
{
    public function __construct(
        private readonly UserQuery $userQuery,
    ) {
    }

    public function register(RegistryManagerInterface $manager): void
    {
        $manager->register('org.dashboard.distribution', function (DistributionBuilder $builder) {
            $stats = $this->userQuery->getActiveUserStats();

            $employeesCount   = $stats->employeesCount;
            $employeesPercent = $employeesCount !== 0 ? $employeesCount / $stats->totalCount * 100 : 0;
            $value            = sprintf($this->getTemplate(), $employeesCount, trans('global.person'), $employeesPercent);
            $builder->segment('کارمندان')
                ->value($value)
                ->percent((int) $employeesPercent)
                ->color('green')
                ->order(1);

            $customerCount   = $stats->customersCount;
            $customerPercent = $customerCount !== 0 ? $customerCount / $stats->totalCount * 100 : 0;
            $value           = sprintf($this->getTemplate(), $customerCount, trans('global.person'), $customerPercent);
            $builder->segment('مشتریان')
                ->value($value)
                ->percent((int) $customerPercent)
                ->color('blue')
                ->order(2);

            $userCount   = $stats->usersCount;
            $userPercent = $userCount !== 0 ? $userCount / $stats->totalCount * 100 : 0;
            $value       = sprintf($this->getTemplate(), $userCount, trans('global.person'), $userPercent);
            $builder->segment('کاربران عادی')
                ->value($value)
                ->percent((int) $userPercent)
                ->color('gray')
                ->order(3);
        });
    }

    private function getTemplate(): string
    {
        return '%d %s (%d%%)';
    }
}
