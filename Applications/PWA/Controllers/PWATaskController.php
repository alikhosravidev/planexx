<?php

declare(strict_types=1);

namespace Applications\PWA\Controllers;

use App\Core\BPMS\Entities\Task;
use Applications\Contracts\BaseWebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PWATaskController extends BaseWebController
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $filters = ['assignee_id' => $user->id];

        if ($request->filled('status')) {
            $status = $request->get('status');

            if ($status === 'pending') {
                $filters['completed_at']['is_null'] = true;
            } elseif ($status === 'completed') {
                $filters['completed_at']['not_null'] = true;
            }
        }

        $queryParams = [
            'includes' => 'workflow,currentState',
            'filter'   => $filters,
            'sort'     => '-priority,-created_at',
            'per_page' => $request->get('per_page', 50),
        ];

        $response = $this->apiGet('api.v1.client.bpms.tasks.index', $queryParams);

        $tasks = $response['result'] ?? [];
        $stats = $this->calculateStats($tasks);

        return view('pwa::pages.tasks', [
            'tasks'        => $tasks,
            'pagination'   => $response['meta']['pagination'] ?? [],
            'stats'        => $stats,
            'activeFilter' => $request->get('status', 'all'),
        ]);
    }

    public function show(Task $task): View
    {
        $includes = [
            'attachments',
            'workflow.states',
            'workflow.department',
            'workflow.owner',
            'currentState',
            'assignee',
            'creator',
            'followUps.creator.avatar',
            'followUps.attachments',
            'followUps.previousAssignee.avatar',
            'followUps.newAssignee.avatar',
            'followUps.previousState',
            'followUps.newState',
        ];
        $response = $this->apiGet('api.v1.client.bpms.tasks.show', [
            'task'     => $task->id,
            'includes' => implode(',', $includes),
        ]);

        return view('pwa::pages.task-detail', [
            'task' => $response['result'] ?? [],
        ]);
    }

    private function calculateStats(array $tasks): array
    {
        $total     = count($tasks);
        $pending   = 0;
        $completed = 0;

        foreach ($tasks as $task) {
            if (isset($task['is_completed']) && $task['is_completed']) {
                $completed++;
            } else {
                $pending++;
            }
        }

        return [
            'all'       => $total,
            'pending'   => $pending,
            'completed' => $completed,
        ];
    }
}
