<?php

declare(strict_types=1);

namespace App\Core\BPMS\Events;

use App\Core\BPMS\Entities\Task;
use App\Core\BPMS\Entities\WorkflowState;

final class TaskCompleted
{
    public function __construct(
        public Task $task,
        public WorkflowState $finalState,
        public int $actorId,
    ) {
    }
}
