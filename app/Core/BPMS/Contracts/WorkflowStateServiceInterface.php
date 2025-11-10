<?php

declare(strict_types=1);

namespace App\Core\BPMS\Contracts;

use App\Core\BPMS\DTOs\WorkflowStateDTO;
use App\Core\BPMS\Entities\WorkflowState;

interface WorkflowStateServiceInterface
{
    public function create(WorkflowStateDTO $dto): WorkflowState;

    public function update(WorkflowState $state, WorkflowStateDTO $dto): WorkflowState;

    public function delete(WorkflowState $state): bool;
}
