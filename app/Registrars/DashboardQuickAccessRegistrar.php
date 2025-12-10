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
            $builder->item('مدیریت اسناد و فایل‌ها', 'document')
                ->route('web.documents.index')
                ->icon('fa-solid fa-folder-open')
                ->color('amber')
                ->enabled()
                ->order(2);

            $builder->item('پایگاه تجربه سازمانی', 'okb')
                ->url('#')
                ->icon('fa-solid fa-book')
                ->color('teal')
                ->enabled(false)
                ->order(4);

            $builder->item('مالی و وصول مطالبات', 'receivable')
                ->url('#')
                ->icon('fa-solid fa-coins')
                ->color('green')
                ->enabled(false)
                ->order(5);

            $builder->item('CRM', 'crm')
                ->url('#')
                ->icon('fa-solid fa-users-line')
                ->color('purple')
                ->enabled(false)
                ->order(6);
        });
    }
}
