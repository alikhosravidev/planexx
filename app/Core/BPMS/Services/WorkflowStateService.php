<?php

declare(strict_types=1);

namespace App\Core\BPMS\Services;

use App\Core\BPMS\Contracts\WorkflowStateServiceInterface;
use App\Core\BPMS\DTOs\WorkflowStateDTO;
use App\Core\BPMS\Entities\Workflow;
use App\Core\BPMS\Entities\WorkflowState;
use App\Core\BPMS\Enums\WorkflowStatePosition;
use App\Core\BPMS\Repositories\TaskRepository;
use App\Core\BPMS\Repositories\WorkflowStateRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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

    /**
     * @param  Workflow  $workflow
     * @param  array  $states
     * @return void
     * @throws \Throwable
     */
    public function sync(Workflow $workflow, array $states): void
    {
        DB::transaction(function () use ($workflow, $states) {
            $formattedStates = $this->prepareStatesData($workflow, $states);

            $this->pruneObsoleteStates($workflow, $formattedStates);

            $this->persistStates($workflow, $formattedStates);
        });
    }

    protected function prepareStatesData(Workflow $workflow, array $states): Collection
    {
        return collect($states)->map(function ($stateData, $index) use ($workflow) {
            return [
                'id'                  => $stateData['id'] ?? null,
                'workflow_id'         => $workflow->id,
                'name'                => $stateData['name'] ?? '',
                'slug'                => $this->generateSlug($stateData, $index),
                'description'         => $stateData['description']         ?? null,
                'color'               => $stateData['color']               ?? null,
                'order'               => $stateData['order']               ?? null,
                'default_assignee_id' => $stateData['default_assignee_id'] ?? null,
                'is_active'           => true,
                'position'            => $this->resolvePosition($stateData['position'] ?? 'middle'),
                'allowed_roles'       => $stateData['allowed_roles'] ?? [],
            ];
        });
    }

    protected function pruneObsoleteStates(Workflow $workflow, Collection $formattedStates): void
    {
        $idsToKeep = $formattedStates->pluck('id')->filter()->all();

        $workflow->states()
            ->whereNotIn('id', $idsToKeep)
            ->delete();
    }

    protected function persistStates(Workflow $workflow, Collection $formattedStates): void
    {
        foreach ($formattedStates as $attributes) {
            $allowedRoles = $attributes['allowed_roles'] ?? [];
            unset($attributes['allowed_roles']);

            $state = $workflow->states()->updateOrCreate(
                ['id' => $attributes['id']],
                $attributes
            );

            $this->syncStateAllowedRoles($state, $allowedRoles);
        }
    }

    private function resolvePosition(string $positionString): WorkflowStatePosition
    {
        return match ($positionString) {
            'start'         => WorkflowStatePosition::Start,
            'final-success' => WorkflowStatePosition::FinalSuccess,
            'final-failed'  => WorkflowStatePosition::FinalFailed,
            'final-closed'  => WorkflowStatePosition::FinalClosed,
            default         => WorkflowStatePosition::Middle,
        };
    }

    private function generateSlug(array $stateData, int $index): string
    {
        return $stateData['slug'] ?? $stateData['name'] ?? 'state-' . ($index + 1);
    }

    private function syncStateAllowedRoles(WorkflowState $state, array $allowedRoles): void
    {
        if (empty($allowedRoles)) {
            $state->allowedRoles()->detach();

            return;
        }

        $roleIds = array_filter(array_map('intval', $allowedRoles));

        if (method_exists($state, 'allowedRoles')) {
            $state->allowedRoles()->sync($roleIds);
        }
    }
}
