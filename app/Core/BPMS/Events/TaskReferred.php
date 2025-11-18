<?php

declare(strict_types=1);

namespace App\Core\BPMS\Events;

use App\Core\BPMS\Entities\Task;

final class TaskReferred
{
    public function __construct(
        public Task $task,
        public int $previousAssigneeId,
        public int $newAssigneeId,
        public int $actorId,
    ) {
    }
}
