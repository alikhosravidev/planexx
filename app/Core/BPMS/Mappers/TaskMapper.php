<?php

declare(strict_types=1);

namespace App\Core\BPMS\Mappers;

use App\Core\BPMS\DTOs\TaskDTO;
use App\Core\BPMS\Entities\Task;
use App\Core\BPMS\Enums\TaskPriority;
use App\Core\BPMS\Http\Requests\StoreTaskRequest;
use App\Core\BPMS\Http\Requests\UpdateTaskRequest;
use App\Domains\User\UserId;
use App\Domains\Workflow\WorkflowId;
use App\Domains\WorkflowState\WorkflowStateId;
use App\ValueObjects\Hours;
use App\ValueObjects\Slug;

class TaskMapper
{
    public function fromStoreRequest(StoreTaskRequest $request): TaskDTO
    {
        return new TaskDTO(
            title         : $request->string('title')->toString(),
            workflowId    : new WorkflowId((int) $request->input('workflow_id')),
            assigneeId    : new UserId((int) $request->input('assignee_id')),
            createdBy     : new UserId($request->user()->id),
            priority      : TaskPriority::from((int) $request->input('priority')),
            slug          : $request->filled('slug')
                ? new Slug($request->string('slug')->toString())
                : null,
            description   : $request->input('description'),
            currentStateId: $request->filled('current_state_id')
                ? new WorkflowStateId((int) $request->input('current_state_id'))
                : null,
            estimatedHours: $request->filled('estimated_hours')
                ? new Hours((int) $request->input('estimated_hours'))
                : null,
            nextFollowUpDate: $request->filled('due_date')
                ? new \DateTimeImmutable($request->input('due_date'))
                : null,
        );
    }

    public function fromUpdateRequest(UpdateTaskRequest $request, Task $task): TaskDTO
    {
        return new TaskDTO(
            title         : $request->input('title', $task->title),
            workflowId    : new WorkflowId((int) $request->input('workflow_id', $task->workflow_id)),
            assigneeId    : new UserId((int) $request->input('assignee_id', $task->assignee_id)),
            createdBy     : new UserId($task->created_by),
            priority      : TaskPriority::from((int) $request->input('priority', $task->priority->value)),
            slug          : $request->filled('slug')
                ? new Slug($request->string('slug')->toString())
                : ($task->slug ? new Slug($task->slug) : null),
            description   : $request->input('description', $task->description),
            currentStateId: $request->filled('current_state_id')
                ? new WorkflowStateId((int) $request->input('current_state_id'))
                : ($task->current_state_id ? new WorkflowStateId($task->current_state_id) : null),
            estimatedHours: $request->filled('estimated_hours')
                ? new Hours((int) $request->input('estimated_hours'))
                : ($task->estimated_hours ? new Hours((int) $task->estimated_hours) : null),
            nextFollowUpDate: $request->filled('due_date')
                ? new \DateTimeImmutable($request->input('due_date'))
                : $task->next_follow_up_date,
        );
    }
}
