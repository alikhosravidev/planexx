<?php

declare(strict_types=1);

namespace App\Core\BPMS\Contracts;

use App\Core\BPMS\DTOs\WorkflowDTO;
use App\Core\BPMS\Entities\Workflow;

interface WorkflowServiceInterface
{
    public function create(WorkflowDTO $dto): Workflow;

    public function update(Workflow $workflow, WorkflowDTO $dto): Workflow;

    public function delete(Workflow $workflow): bool;
}
