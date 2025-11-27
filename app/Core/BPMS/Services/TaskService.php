<?php

declare(strict_types=1);

namespace App\Core\BPMS\Services;

use App\Core\BPMS\Contracts\FollowUpServiceInterface;
use App\Core\BPMS\Contracts\TaskServiceInterface;
use App\Core\BPMS\DTOs\FollowUpDTO;
use App\Core\BPMS\DTOs\TaskDTO;
use App\Core\BPMS\Entities\Task;
use App\Core\BPMS\Entities\WorkflowState;
use App\Core\BPMS\Enums\FollowUpType;
use App\Core\BPMS\Enums\WorkflowStatePosition;
use App\Core\BPMS\Events\TaskCompleted;
use App\Core\BPMS\Events\TaskCreated;
use App\Core\BPMS\Events\TaskReferred;
use App\Core\BPMS\Events\TaskStateChanged;
use App\Core\BPMS\Repositories\TaskRepository;
use App\Core\BPMS\Repositories\WorkflowStateRepository;
use App\Domains\Task\TaskId;
use App\Domains\User\UserId;
use App\Domains\WorkflowState\WorkflowStateId;

readonly class TaskService implements TaskServiceInterface
{
    public function __construct(
        private TaskRepository $taskRepository,
        private WorkflowStateRepository $workflowStateRepository,
        private FollowUpServiceInterface $followUpService,
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

    public function changeState(Task $task, WorkflowState $newState, int $actorId): Task
    {
        $this->validateStateInWorkflow($newState->id, $task->workflow_id);

        $previousState = $task->currentState;

        $task = $this->taskRepository->update($task->id, [
            'current_state_id' => $newState->id,
        ]);

        $followUpDTO = new FollowUpDTO(
            taskId: new TaskId($task->id),
            type: FollowUpType::STATE_TRANSITION,
            createdBy: new UserId($actorId),
            content: null,
            newAssigneeId: null,
            newStateId: new WorkflowStateId($newState->id),
        );

        $this->followUpService->create(
            dto: $followUpDTO,
            previousAssigneeId: null,
            previousStateId: $previousState->id,
        );

        TaskStateChanged::dispatch($task, $previousState, $newState, $actorId);

        return $task;
    }

    public function refer(Task $task, int $newAssigneeId, int $actorId): Task
    {
        $previousAssigneeId = $task->assignee_id;

        $task = $this->taskRepository->update($task->id, [
            'assignee_id' => $newAssigneeId,
        ]);

        $followUpDTO = new FollowUpDTO(
            taskId: new TaskId($task->id),
            type: FollowUpType::USER_ACTION,
            createdBy: new UserId($actorId),
            content: null,
            newAssigneeId: new UserId($newAssigneeId),
            newStateId: null,
        );

        $this->followUpService->create(
            dto: $followUpDTO,
            previousAssigneeId: $previousAssigneeId,
            previousStateId: null,
        );

        TaskReferred::dispatch($task, $previousAssigneeId, $newAssigneeId, $actorId);

        return $task;
    }

    public function complete(Task $task, int $actorId): Task
    {
        $finalSuccessState = $this->getFinalSuccessState($task->workflow_id);

        $task = $this->taskRepository->update($task->id, [
            'current_state_id' => $finalSuccessState->id,
            'completed_at'     => now(),
        ]);

        $followUpDTO = new FollowUpDTO(
            taskId: new TaskId($task->id),
            type: FollowUpType::USER_ACTION,
            createdBy: new UserId($actorId),
            content: 'Task completed',
            newAssigneeId: null,
            newStateId: new WorkflowStateId($finalSuccessState->id),
        );

        $this->followUpService->create(
            dto: $followUpDTO,
            previousAssigneeId: null,
            previousStateId: $task->current_state_id !== $finalSuccessState->id ? $task->current_state_id : null,
        );

        TaskCompleted::dispatch($task, $finalSuccessState, $actorId);

        return $task;
    }

    public function delete(Task $task): bool
    {
        return $this->taskRepository->delete($task->id);
    }

    private function getStartState(int $workflowId): WorkflowState
    {
        $state = $this->workflowStateRepository
            ->makeModel()
            ->where('workflow_id', $workflowId)
            ->where('position', WorkflowStatePosition::START)
            ->where('is_active', true)
            ->first();

        if (! $state) {
            throw new \LogicException("No START state found for workflow ID {$workflowId}");
        }

        return $state;
    }

    private function getFinalSuccessState(int $workflowId): WorkflowState
    {
        $state = $this->workflowStateRepository
            ->makeModel()
            ->where('workflow_id', $workflowId)
            ->where('position', WorkflowStatePosition::FINAL_SUCCESS)
            ->where('is_active', true)
            ->first();

        if (! $state) {
            throw new \LogicException("No FINAL_SUCCESS state found for workflow ID {$workflowId}");
        }

        return $state;
    }

    private function validateStateInWorkflow(int $stateId, int $workflowId): void
    {
        $state = $this->workflowStateRepository->find($stateId);

        if (! $state || $state->workflow_id !== $workflowId) {
            throw new \InvalidArgumentException("State ID {$stateId} does not belong to workflow ID {$workflowId}");
        }
    }
}
