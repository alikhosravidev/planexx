<?php

declare(strict_types=1);

namespace App\Core\BPMS\Repositories;

use App\Contracts\Repository\BaseRepository;
use App\Core\BPMS\Entities\Task;

class TaskRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id'               => '=',
        'workflow_id'      => '=',
        'current_state_id' => '=',
        'assignee_id'      => '=',
        'created_by'       => '=',
        'title'            => 'like',
        'priority'         => '=',
    ];

    public array $sortableFields = [
        'id', 'title', 'priority', 'due_date', 'created_at', 'updated_at',
    ];

    public function model(): string
    {
        return Task::class;
    }
}
