<?php

declare(strict_types=1);

namespace App\Core\BPMS\Traits;

use App\Core\BPMS\Entities\Task;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property \Illuminate\Database\Eloquent\Collection<Task> $tasks
 */
trait HasTasks
{
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assignee_id')
            ->orderBy('created_at', 'desc');
    }
}
