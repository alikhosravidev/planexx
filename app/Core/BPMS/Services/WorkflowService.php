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
        private WorkflowStateService $workflowStateService
    ) {
    }

    public function create(WorkflowDTO $dto): Workflow
    {
        $workflow = $this->workflowRepository->create($dto->toArray());
        $this->syncAllowedRoles($workflow, $dto->allowedRoles);
        $this->workflowStateService->sync($workflow, $dto->states);

        WorkflowCreated::dispatch($workflow);

        return $workflow;
    }

    private function syncAllowedRoles(Workflow $workflow, array $allowedRoles): void
    {
        if (empty($allowedRoles)) {
            return;
        }

        $roleIds = array_filter(array_map('intval', $allowedRoles));

        if (method_exists($workflow, 'allowedRoles')) {
            $workflow->allowedRoles()->sync($roleIds);
        }
    }

    public function update(Workflow $workflow, WorkflowDTO $dto): Workflow
    {
        $workflow = $this->workflowRepository->update($workflow->id, $dto->toArray());

        $this->syncAllowedRoles($workflow, $dto->allowedRoles);
        $this->workflowStateService->sync($workflow, $dto->states);

        return $workflow;
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
