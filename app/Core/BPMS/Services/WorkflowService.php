<?php

declare(strict_types=1);

namespace App\Core\BPMS\Services;

use App\Core\BPMS\Contracts\WorkflowServiceInterface;
use App\Core\BPMS\DTOs\WorkflowDTO;
use App\Core\BPMS\Entities\Workflow;
use App\Core\BPMS\Events\WorkflowCreated;
use App\Core\BPMS\Repositories\WorkflowRepository;
use App\Core\BPMS\Repositories\WorkflowStateRepository;

readonly class WorkflowService implements WorkflowServiceInterface
{
    public function __construct(
        private WorkflowRepository $workflowRepository,
        private WorkflowStateRepository $workflowStateRepository,
    ) {
    }

    public function create(WorkflowDTO $dto): Workflow
    {
        $data = $dto->toArray();

        $workflow = $this->workflowRepository->create($data);

        WorkflowCreated::dispatch($workflow);

        return $workflow;
    }

    public function update(Workflow $workflow, WorkflowDTO $dto): Workflow
    {
        $data = $dto->toArray();

        return $this->workflowRepository->update($workflow->id, $data);
    }

    public function delete(Workflow $workflow): bool
    {
        $hasStates = $this->workflowStateRepository
            ->makeModel()
            ->where('workflow_id', $workflow->id)
            ->exists();

        if ($hasStates) {
            throw new \LogicException('Cannot delete workflow that has states');
        }

        return $this->workflowRepository->delete($workflow->id);
    }
}
