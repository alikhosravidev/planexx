<?php

declare(strict_types=1);

namespace App\Core\BPMS\Services;

use App\Core\BPMS\Contracts\WorkflowStateServiceInterface;
use App\Core\BPMS\DTOs\WorkflowStateDTO;
use App\Core\BPMS\Entities\WorkflowState;
use App\Core\BPMS\Repositories\TaskRepository;
use App\Core\BPMS\Repositories\WorkflowStateRepository;
use LogicException;

readonly class WorkflowStateService implements WorkflowStateServiceInterface
{
    public function __construct(
        private WorkflowStateRepository $workflowStateRepository,
        private TaskRepository $taskRepository,
    ) {
    }

    public function create(WorkflowStateDTO $dto): WorkflowState
    {
        $data = $dto->toArray();

        if ($data['order'] === null) {
            $data['order'] = $this->getNextOrder($dto->workflowId->value);
        }

        return $this->workflowStateRepository->create($data);
    }

    public function update(WorkflowState $state, WorkflowStateDTO $dto): WorkflowState
    {
        $data = $dto->toArray();

        return $this->workflowStateRepository->update($state->id, $data);
    }

    public function delete(WorkflowState $state): bool
    {
        $hasTasks = $this->taskRepository
            ->makeModel()
            ->where('current_state_id', $state->id)
            ->exists();

        if ($hasTasks) {
            throw new LogicException('Cannot delete workflow state that is assigned to tasks');
        }

        return $this->workflowStateRepository->delete($state->id);
    }

    private function getNextOrder(int $workflowId): int
    {
        $maxOrder = $this->workflowStateRepository
            ->makeModel()
            ->where('workflow_id', $workflowId)
            ->max('order');

        return $maxOrder !== null ? ((int) $maxOrder) + 1 : 0;
    }
}
