<?php

declare(strict_types=1);

namespace App\Core\BPMS\Registrars;

use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryManagerInterface;
use App\Core\BPMS\Repositories\TaskRepository;
use App\Core\BPMS\Repositories\WorkflowRepository;
use App\Services\Stats\StatBuilder;

class BPMSStatsRegistrar implements RegistrarInterface
{
    public function __construct(
        private readonly WorkflowRepository $workflowRepository,
        private readonly TaskRepository $taskRepository,
    ) {
    }

    public function register(RegistryManagerInterface $manager): void
    {
        $manager->register('bpms.dashboard.stats', function (StatBuilder $builder) {
            $activeWorkflows = $this->workflowRepository
                ->newQuery()
                ->where('is_active', 1)
                ->count();

            $stats = $this->getTaskStats();

            $builder->stat('فرایندهای فعال', 'bpms-active-workflows')
                ->value($activeWorkflows)
                ->icon('fa-solid fa-diagram-project')
                ->color('indigo')
                ->order(1);

            $builder->stat('کارهای در جریان', 'bpms-tasks-in-progress')
                ->value($stats['in_progress'] ?? 0)
                ->icon('fa-solid fa-spinner')
                ->color('blue')
                ->order(2);

            $builder->stat('کارهای تکمیل شده', 'bpms-tasks-completed')
                ->value($stats['completed'] ?? 0)
                ->icon('fa-solid fa-check-circle')
                ->color('green')
                ->order(3);

            $builder->stat('کارهای معوق', 'bpms-tasks-overdue')
                ->value($stats['overdue'] ?? 0)
                ->icon('fa-solid fa-clock')
                ->color('red')
                ->order(4);
        });
    }

    private function getTaskStats(): array
    {
        $row = $this->taskRepository
            ->newQuery()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN completed_at IS NOT NULL THEN 1 ELSE 0 END) as completed')
            ->selectRaw('SUM(CASE WHEN completed_at IS NULL AND due_date IS NOT NULL AND due_date < NOW() THEN 1 ELSE 0 END) as overdue')
            ->first();

        $total     = (int) ($row->total ?? 0);
        $completed = (int) ($row->completed ?? 0);

        return [
            'total'       => $total,
            'completed'   => $completed,
            'overdue'     => (int) ($row->overdue ?? 0),
            'in_progress' => max(0, $total - $completed),
        ];
    }
}
