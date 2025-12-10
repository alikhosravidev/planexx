<?php

declare(strict_types=1);

namespace App\Core\BPMS\Registrars;

use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryManagerInterface;
use App\Services\QuickAccess\QuickAccessBuilder;

class BPMSQuickAccessRegistrar implements RegistrarInterface
{
    public function register(RegistryManagerInterface $manager): void
    {
        $manager->register('dashboard.quick-access', function (QuickAccessBuilder $builder) {
            $builder->item('مدیریت وظایف', 'task-manager')
                ->route('web.bpms.dashboard')
                ->icon('fa-solid fa-list-check')
                ->color('indigo')
                ->enabled()
                ->order(3);
        });

        $manager->register('bpms.dashboard.quick-access', function (QuickAccessBuilder $builder) {
            $builder->item('مدیریت فرایندها', 'bpms-workflows')
                ->route('web.bpms.workflows.index')
                ->icon('fa-solid fa-diagram-project')
                ->color('indigo')
                ->enabled()
                ->order(1);

            $builder->item('ایجاد فرایند جدید', 'bpms-workflows-create')
                ->route('web.bpms.workflows.create')
                ->icon('fa-solid fa-plus-circle')
                ->color('green')
                ->enabled()
                ->order(2);

            $builder->item('کارهای جاری', 'bpms-tasks-active')
                ->route('web.bpms.tasks.index')
                ->icon('fa-solid fa-list-check')
                ->color('blue')
                ->enabled(false)
                ->order(3);

            $builder->item('گزارش‌گیری', 'bpms-reports')
                ->url('#')
                ->icon('fa-solid fa-chart-bar')
                ->color('purple')
                ->enabled(false)
                ->order(4);
        });
    }
}
