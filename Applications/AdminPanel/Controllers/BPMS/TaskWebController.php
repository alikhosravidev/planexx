<?php

declare(strict_types=1);

namespace Applications\AdminPanel\Controllers\BPMS;

use App\Core\BPMS\Entities\Task;
use App\Core\BPMS\Enums\TaskPriority;
use Applications\Contracts\BaseWebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TaskWebController extends BaseWebController
{
    public function index(Request $request): View
    {
        $pageTitle = 'کارها و وظایف';

        $queryParams = $request->except('filter');
        $filters     = [];

        if ($request->filled('workflow_id')) {
            $filters['workflow_id'] = $request->get('workflow_id');
        }

        if ($request->filled('assignee_id')) {
            $filters['assignee_id'] = $request->get('assignee_id');
        }

        if ($request->filled('priority')) {
            $filters['priority'] = $request->get('priority');
        }

        $queryParams['includes']  = 'workflow,currentState,assignee,creator';
        $queryParams['withCount'] = 'followUps,attachments';
        $queryParams['filter']    = $filters;

        $response = $this->apiGet('api.v1.admin.bpms.tasks.index', $queryParams);

        // Calculate stats from the response
        $tasks = $response['result'] ?? [];
        $stats = $this->calculateStats($tasks);

        return view('panel::tasks.index', [
            'tasks'      => $tasks,
            'pagination' => $response['meta']['pagination'] ?? [],
            'pageTitle'  => $pageTitle,
            'stats'      => $stats,
        ]);
    }

    public function show(Task $task): View
    {
        $response = $this->apiGet('api.v1.admin.bpms.tasks.show', [
            'task'     => $task->id,
            'includes' => 'attachments,workflow.states,workflow.department,workflow.owner,currentState,assignee,creator,followUps.creator,followUps.attachments',
        ]);

        return view('panel::tasks.show', [
            'task' => $response['result'] ?? [],
        ]);
    }

    public function create(Request $request): View
    {
        $workflowId = $request->get('workflow_id');

        return view('panel::tasks.add-or-edit', [
            'workflowId' => $workflowId,
        ]);
    }

    public function edit(Task $task): View
    {
        $response = $this->apiGet('api.v1.admin.bpms.tasks.show', [
            'task'     => $task->id,
            'includes' => 'workflow,workflow.states,currentState,assignee,creator',
        ]);

        return view('panel::tasks.add-or-edit', [
            'task' => $response['result'] ?? [],
        ]);
    }

    private function calculateStats(array $tasks): array
    {
        $total   = count($tasks);
        $urgent  = 0;
        $overdue = 0;
        $today   = 0;

        foreach ($tasks as $task) {
            if (isset($task['priority']) && $task['priority']['value'] === TaskPriority::URGENT->value) {
                $urgent++;
            }

            if (isset($task['remaining_days'])) {
                if ($task['remaining_days'] < 0) {
                    $overdue++;
                } elseif ($task['remaining_days'] === 0) {
                    $today++;
                }
            }
        }

        return [
            'total'   => $total,
            'urgent'  => $urgent,
            'overdue' => $overdue,
            'today'   => $today,
        ];
    }
}
