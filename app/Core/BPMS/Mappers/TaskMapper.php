<?php

declare(strict_types=1);

namespace App\Core\BPMS\Mappers;

use App\Core\BPMS\DTOs\AddNoteDTO;
use App\Core\BPMS\DTOs\ForwardTaskDTO;
use App\Core\BPMS\DTOs\TaskDTO;
use App\Core\BPMS\DTOs\UpdateTaskActionDTO;
use App\Core\BPMS\Entities\Task;
use App\Core\BPMS\Enums\TaskAction;
use App\Core\BPMS\Enums\TaskPriority;
use App\Core\BPMS\Http\Requests\API\V1\Admin\StoreTaskRequest;
use App\Core\BPMS\Http\Requests\API\V1\Admin\UpdateTaskRequest;
use App\Domains\User\UserId;
use App\Domains\Workflow\WorkflowId;
use App\Domains\WorkflowState\WorkflowStateId;
use App\ValueObjects\Hours;

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
            description   : $request->input('description', $task->description),
            currentStateId: $request->filled('current_state_id')
                ? new WorkflowStateId((int) $request->input('current_state_id'))
                : ($task->current_state_id ? new WorkflowStateId($task->current_state_id) : null),
            estimatedHours: $request->filled('estimated_hours')
                ? new Hours((int) $request->input('estimated_hours'))
                : ($task->estimated_hours ? new Hours((int) $task->estimated_hours) : null),
            nextFollowUpDate: $request->filled('next_follow_up_date')
                                ? new \DateTimeImmutable($request->input('next_follow_up_date'))
                                : $task->next_follow_up_date,
            dueDate: $request->filled('due_date')
                                ? new \DateTimeImmutable($request->input('due_date'))
                                : $task->next_follow_up_date,
        );
    }

    public function toUpdateActionDTO(UpdateTaskRequest $request, Task $task): UpdateTaskActionDTO
    {
        $action  = TaskAction::fromString($request->input('action', 'edit'));
        $actorId = $request->user()->id;

        return match ($action) {
            TaskAction::EDIT => new UpdateTaskActionDTO(
                action: $action,
                taskDTO: $this->fromUpdateRequest($request, $task),
            ),

            TaskAction::ADD_NOTE => new UpdateTaskActionDTO(
                action: $action,
                addNoteDTO: new AddNoteDTO(
                    content: $request->input('content'),
                    actorId: $actorId,
                    nextFollowUpDate: $request->filled('next_follow_up_date')
                        ? new \DateTimeImmutable($request->input('next_follow_up_date'))
                        : null,
                    attachment: $request->file('attachment'),
                ),
            ),

            TaskAction::FORWARD => new UpdateTaskActionDTO(
                action: $action,
                forwardTaskDTO: new ForwardTaskDTO(
                    newAssigneeId: (int) $request->input('assignee_id'),
                    actorId: $actorId,
                    note: $request->input('description'),
                    nextStateId: $request->filled('next_state_id')
                        ? (int) $request->input('next_state_id')
                        : null,
                ),
            ),
        };
    }
}
