<?php

declare(strict_types=1);

namespace App\Core\Organization\Registrars;

use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryManagerInterface;
use App\Core\Organization\Entities\Department;
use App\Core\Organization\Repositories\DepartmentRepository;
use App\Core\Organization\Repositories\RoleRepository;
use App\Domains\User\UserQuery;
use App\Services\Stats\StatBuilder;

class OrganizationStatsRegistrar implements RegistrarInterface
{
    public function __construct(
        private readonly UserQuery $userQuery,
        private readonly DepartmentRepository $departmentRepository,
        private readonly RoleRepository $roleRepository,
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

            $builder->stat('مشتریان', 'active-users')
                ->value($stats->customersCount)
                ->icon('fa-solid fa-handshake')
                ->color('orange')
                ->order(4);

        });

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

            $builder->stat('دپارتمان‌ها', 'org-active-departments')
                ->value($this->departmentRepository->newQuery()->where('is_active', 1)->count())
                ->icon('fa-solid fa-sitemap')
                ->color('purple')
                ->order(3);

            $builder->stat('نقش‌های کاربری', 'org-roles')
                ->value($this->roleRepository->newQuery()->count())
                ->icon('fa-solid fa-shield-halved')
                ->color('orange')
                ->order(4);
        });

        $manager->register('org.department.stats', function (StatBuilder $builder) {
            $stats = $this->departmentRepository
                ->newQuery()
                ->selectRaw('COUNT(DISTINCT ' . Department::TABLE . '.id) as total_departments')
                ->selectRaw('SUM(CASE WHEN ' . Department::TABLE . '.is_active = 1 THEN 1 ELSE 0 END) as active_departments')
                ->selectRaw('COUNT(DISTINCT ' . Department::USER_PIVOT_TABLE . '.user_id) as total_employees')
                ->leftJoin(
                    Department::USER_PIVOT_TABLE,
                    Department::TABLE . '.id',
                    '=',
                    Department::USER_PIVOT_TABLE . '.department_id'
                )
                ->first();

            $builder->stat('کل دپارتمان‌ها', 'org-total-departments')
                ->value($stats->total_departments ?? 0)
                ->icon('fa-solid fa-building')
                ->color('blue')
                ->order(1);

            $builder->stat('دپارتمان‌های فعال', 'org-active-departments')
                ->value($stats->active_departments ?? 0)
                ->icon('fa-solid fa-check-circle')
                ->color('green')
                ->order(2);

            $builder->stat('کل کارمندان', 'org-employees')
                ->value($stats->total_employees ?? 0)
                ->icon('fa-solid fa-shield-halved')
                ->color('purple')
                ->order(3);

            $builder->stat('نقش های سازمانی', 'org-roles')
                ->value($this->roleRepository->newQuery()->count())
                ->icon('fa-solid fa-shield-halved')
                ->color('orange')
                ->order(4);
        });
    }
}
