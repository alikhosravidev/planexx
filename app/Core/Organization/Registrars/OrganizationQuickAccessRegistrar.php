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
        $manager->register('dashboard.quick-access', function (QuickAccessBuilder $builder) {
            $builder->item('ساختار سازمانی', 'organization')
                ->route('web.org.dashboard')
                ->icon('fa-solid fa-sitemap')
                ->color('blue')
                ->enabled()
                ->order(1);
        });

        $manager->register('org.dashboard.quick-access', function (QuickAccessBuilder $builder) {
            $builder->item('مدیریت کاربران', 'org-user-management')
                ->route('web.org.users.index')
                ->icon('fa-solid fa-users')
                ->color('blue')
                ->enabled()
                ->order(1);

            $builder->item('دپارتمان‌ها', 'org-departments')
                ->route('web.org.departments.index')
                ->icon('fa-solid fa-sitemap')
                ->color('purple')
                ->enabled()
                ->order(2);

            $builder->item('برچسب‌ها', 'org-job-positions')
                ->route('web.app.tags.index')
                ->icon('fa-solid fa-briefcase')
                ->color('teal')
                ->enabled()
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
