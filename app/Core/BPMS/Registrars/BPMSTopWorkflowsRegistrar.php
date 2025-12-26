<?php

declare(strict_types=1);

namespace App\Core\BPMS\Registrars;

use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryManagerInterface;
use App\Core\BPMS\Entities\Task;
use App\Core\BPMS\Entities\Workflow;
use App\Core\BPMS\Repositories\TaskRepository;
use App\Core\BPMS\Repositories\WorkflowRepository;
use App\Services\Stats\StatBuilder;

class BPMSTopWorkflowsRegistrar implements RegistrarInterface
{
    public function __construct(
        private readonly WorkflowRepository $workflowRepository,
        private readonly TaskRepository $taskRepository,
    ) {
    }

    public function register(RegistryManagerInterface $manager): void
    {
        $manager->register('bpms.dashboard.top-workflows', function (StatBuilder $builder) {
            $workflowTable = Workflow::TABLE;
            $taskTable     = Task::TABLE;

            $items = $this->workflowRepository->newQuery()
                ->selectRaw("{$workflowTable}.id, {$workflowTable}.name, {$workflowTable}.slug, {$workflowTable}.department_id, COUNT({$taskTable}.id) as tasks_count")
                ->leftJoin($taskTable, $taskTable . '.workflow_id', '=', $workflowTable . '.id')
                ->with(['department:id,name'])
                ->withCount(['states'])
                ->groupBy("{$workflowTable}.id", "{$workflowTable}.name", "{$workflowTable}.slug", "{$workflowTable}.department_id")
                ->orderByDesc('tasks_count')
                ->limit(4)
                ->get();

            $order = 1;

            foreach ($items as $workflow) {
                $builder->stat($workflow->name, (string) $workflow->id)
                    ->value($workflow->tasks_count)
                    ->payload([
                                'id'           => $workflow->id,
                                'slug'         => $workflow->slug,
                                'department'   => $workflow->department?->name,
                                'states_count' => $workflow->states_count,
                              ])
                    ->icon('fa-solid fa-diagram-project')
                    ->color('indigo')
                    ->order($order);
                ++$order;
            }
        });
    }
}
