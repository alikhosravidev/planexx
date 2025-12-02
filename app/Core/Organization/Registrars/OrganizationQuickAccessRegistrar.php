<?php

declare(strict_types=1);

namespace App\Core\Organization\Registrars;

use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryManagerInterface;
use App\Services\QuickAccess\QuickAccessBuilder;

class OrganizationQuickAccessRegistrar implements RegistrarInterface
{
    public function register(RegistryManagerInterface $manager): void
    {
        $manager->register('org.dashboard.quick-access', function (QuickAccessBuilder $builder) {
            $builder->item('مدیریت کاربران', 'org-user-management')
                ->route('web.org.users.index')
                ->icon('fa-solid fa-users')
                ->color('blue')
                ->enabled(true)
                ->order(1);

            $builder->item('دپارتمان‌ها', 'org-departments')
                ->route('web.org.departments.index')
                ->icon('fa-solid fa-sitemap')
                ->color('purple')
                ->enabled()
                ->order(2);

            $builder->item('موقعیت‌های شغلی', 'org-job-positions')
                ->url('#')
                ->icon('fa-solid fa-briefcase')
                ->color('teal')
                ->enabled(false)
                ->order(3);

            $builder->item('نقش‌ها و دسترسی‌ها', 'org-roles-permissions')
                ->route('web.org.roles.index')
                ->icon('fa-solid fa-shield-halved')
                ->color('green')
                ->enabled()
                ->order(4);
        });
    }
}
