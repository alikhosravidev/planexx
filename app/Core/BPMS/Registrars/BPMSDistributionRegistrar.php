<?php

declare(strict_types=1);

namespace App\Core\BPMS\Registrars;

use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryManagerInterface;
use App\Core\BPMS\Repositories\TaskRepository;
use App\Services\Distribution\DistributionBuilder;

class BPMSDistributionRegistrar implements RegistrarInterface
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
    ) {
    }

    public function register(RegistryManagerInterface $manager): void
    {
        $manager->register('bpms.dashboard.task-distribution', function (DistributionBuilder $builder) {
            $stats           = $this->getTaskDistributionStats();
            $totalForPercent = max(1, (
                ($stats['initial'] ?? 0)
                + ($stats['in_progress'] ?? 0)
                + ($stats['completed'] ?? 0)
                + ($stats['failed'] ?? 0)
            ));

            $initialCount    = $stats['initial']     ?? 0;
            $inProgressCount = $stats['in_progress'] ?? 0;
            $completedCount  = $stats['completed']   ?? 0;
            $failedCount     = $stats['failed']      ?? 0;

            $initialPercent    = (int) round($initialCount / $totalForPercent * 100);
            $inProgressPercent = (int) round($inProgressCount / $totalForPercent * 100);
            $completedPercent  = (int) round($completedCount / $totalForPercent * 100);
            $failedPercent     = (int) round($failedCount / $totalForPercent * 100);

            $builder->segment('مرحله آغازین')
                ->value(sprintf('%d کار (%d%%)', $initialCount, $initialPercent))
                ->percent($initialPercent)
                ->color('slate')
                ->order(1);

            $builder->segment('در حال انجام')
                ->value(sprintf('%d کار (%d%%)', $inProgressCount, $inProgressPercent))
                ->percent($inProgressPercent)
                ->color('blue')
                ->order(2);

            $builder->segment('موفق و بسته شده')
                ->value(sprintf('%d کار (%d%%)', $completedCount, $completedPercent))
                ->percent($completedPercent)
                ->color('green')
                ->order(3);

            $builder->segment('ناموفق')
                ->value(sprintf('%d کار (%d%%)', $failedCount, $failedPercent))
                ->percent($failedPercent)
                ->color('red')
                ->order(4);
        });
    }

    private function getTaskDistributionStats(): array
    {
        $row = $this->taskRepository
            ->newQuery()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN completed_at IS NOT NULL THEN 1 ELSE 0 END) as completed')
            ->selectRaw('SUM(CASE WHEN completed_at IS NULL AND due_date IS NOT NULL AND due_date < NOW() THEN 1 ELSE 0 END) as overdue')
            ->first();

        $total     = (int) ($row->total ?? 0);
        $completed = (int) ($row->completed ?? 0);
        $overdue   = (int) ($row->overdue ?? 0);

        $inProgress = max(0, $total - $completed);
        $initial    = max(0, $inProgress - $overdue);

        return [
            'total'       => $total,
            'completed'   => $completed,
            'overdue'     => $overdue,
            'in_progress' => $inProgress,
            'initial'     => $initial,
            'failed'      => 0,
        ];
    }
}
