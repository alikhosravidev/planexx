<?php

declare(strict_types=1);

namespace App\Core\BPMS\Services;

use App\Core\BPMS\DTOs\AddNoteDTO;
use App\Core\BPMS\DTOs\FollowUpDTO;
use App\Core\BPMS\DTOs\ForwardTaskDTO;
use App\Core\BPMS\DTOs\TaskDTO;
use App\Core\BPMS\DTOs\UpdateTaskActionDTO;
use App\Core\BPMS\Entities\Task;
use App\Core\BPMS\Entities\WorkflowState;
use App\Core\BPMS\Enums\FollowUpType;
use App\Core\BPMS\Enums\WorkflowStatePosition;
use App\Core\BPMS\Events\TaskCreated;
use App\Core\BPMS\Events\TaskReferred;
use App\Core\BPMS\Events\TaskStateChanged;
use App\Core\BPMS\Repositories\TaskRepository;
use App\Core\BPMS\Repositories\WorkflowStateRepository;
use App\Domains\Task\TaskId;
use App\Domains\User\UserId;
use App\Domains\WorkflowState\WorkflowStateId;

readonly class TaskService
{
    public function __construct(
        private TaskRepository $taskRepository,
        private WorkflowStateRepository $workflowStateRepository,
        private FollowUpService $followUpService,
    ) {
    }

    public function create(TaskDTO $dto): Task
    {
        $data = $dto->toArray();

        if ($data['current_state_id'] === null) {
            $startState               = $this->getStartState($dto->workflowId->value);
            $data['current_state_id'] = $startState->id;
        } else {
            $this->validateStateInWorkflow($data['current_state_id'], $dto->workflowId->value);
        }

        $task = $this->taskRepository->create($data);

        TaskCreated::dispatch($task);

        return $task;
    }

    public function update(Task $task, TaskDTO $dto): Task
    {
        $data = $dto->toArray();

        if ($data['current_state_id'] !== null) {
            $this->validateStateInWorkflow($data['current_state_id'], $dto->workflowId->value);
        }

        return $this->taskRepository->update($task->id, $data);
    }

    public function processUpdate(Task $task, UpdateTaskActionDTO $actionDTO): Task
    {
        return match (true) {
            $actionDTO->isEdit()    => $this->update($task, $actionDTO->taskDTO),
            $actionDTO->isAddNote() => $this->addNote($task, $actionDTO->addNoteDTO),
            $actionDTO->isForward() => $this->forwardToNextState($task, $actionDTO->forwardTaskDTO),
        };
    }

    public function delete(Task $task): bool
    {
        return $this->taskRepository->delete($task->id);
    }

    public function addNote(Task $task, AddNoteDTO $dto): Task
    {
        $followUpDTO = new FollowUpDTO(
            taskId: new TaskId($task->id),
            type: FollowUpType::FOLLOW_UP,
            createdBy: new UserId($dto->actorId),
            content: $dto->content,
            newAssigneeId: null,
            newStateId: null,
        );

        $this->followUpService->create(
            dto: $followUpDTO,
            attachment: $dto->attachment,
        );

        if ($dto->nextFollowUpDate) {
            $task = $this->taskRepository->update($task->id, [
                'next_follow_up_date' => $dto->nextFollowUpDate,
            ]);
        }

        return $task;
    }

    public function forwardToNextState(Task $task, ForwardTaskDTO $dto): Task
    {
        $previousAssigneeId = $task->assignee_id;
        $previousStateId    = $task->current_state_id;
        $previousState      = $task->currentState;

        $nextState = null;

        if ($dto->nextStateId) {
            $nextState = $this->workflowStateRepository->find($dto->nextStateId);
        }

        $updateData = ['assignee_id' => $dto->newAssigneeId];

        if ($nextState) {
            $this->validateStateInWorkflow($nextState->id, $task->workflow_id);
            $updateData['current_state_id'] = $nextState->id;
        }

        $task = $this->taskRepository->update($task->id, $updateData);

        $followUpDTO = new FollowUpDTO(
            taskId: new TaskId($task->id),
            type: FollowUpType::STATE_TRANSITION,
            createdBy: new UserId($dto->actorId),
            content: $dto->note,
            newAssigneeId: new UserId($dto->newAssigneeId),
            newStateId: $nextState ? new WorkflowStateId($nextState->id) : null,
        );

        $this->followUpService->create(
            dto: $followUpDTO,
            previousAssigneeId: $previousAssigneeId,
            previousStateId: $previousStateId,
        );

        if ($nextState) {
            event(new TaskStateChanged($task, $previousState, $nextState, $dto->actorId));
        }

        event(new TaskReferred($task, $previousAssigneeId, $dto->newAssigneeId, $dto->actorId));

        return $task;
    }

    private function getStartState(int $workflowId): WorkflowState
    {
        $state = $this->workflowStateRepository
            ->makeModel()
            ->where('workflow_id', $workflowId)
            ->where('position', WorkflowStatePosition::Start)
            ->where('is_active', true)
            ->first();

        if (! $state) {
            throw new \LogicException("No START state found for workflow ID {$workflowId}");
        }

        return $state;
    }

    private function validateStateInWorkflow(int $stateId, int $workflowId): void
    {
        $state = $this->workflowStateRepository->find($stateId);

        if (! $state || $state->workflow_id !== $workflowId) {
            throw new \InvalidArgumentException(
                "State ID {$stateId} does not belong to workflow ID {$workflowId}"
            );
        }
    }
}
