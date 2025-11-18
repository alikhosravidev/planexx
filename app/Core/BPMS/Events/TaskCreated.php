<?php

declare(strict_types=1);

namespace App\Core\BPMS\Events;

use App\Core\BPMS\Entities\Task;
use Illuminate\Foundation\Events\Dispatchable;

final class TaskCreated
{
    use Dispatchable;

    public function __construct(public Task $task)
    {
    }
}
