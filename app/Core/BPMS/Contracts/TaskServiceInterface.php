<?php

declare(strict_types=1);

namespace App\Core\BPMS\Contracts;

use App\Core\BPMS\DTOs\TaskDTO;
use App\Core\BPMS\Entities\Task;
use App\Core\BPMS\Entities\WorkflowState;

interface TaskServiceInterface
{
    public function create(TaskDTO $dto): Task;

    public function update(Task $task, TaskDTO $dto): Task;

    public function changeState(Task $task, WorkflowState $newState, int $actorId): Task;

    public function refer(Task $task, int $newAssigneeId, int $actorId): Task;

    public function complete(Task $task, int $actorId): Task;

    public function delete(Task $task): bool;
}
