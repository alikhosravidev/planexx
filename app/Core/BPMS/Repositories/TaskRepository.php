<?php

declare(strict_types=1);

namespace App\Core\BPMS\Repositories;

use App\Contracts\Repository\BaseRepository;
use App\Core\BPMS\Entities\Task;

class TaskRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id'          => '=',
        'workflow_id' => '=',
        'title'       => 'like',
    ];

    public array $filterableFields = [
        'assignee_id'      => '=',
        'created_by'       => '=',
        'current_state_id' => '=',
        'priority'         => '=',
        'completed_at'     => '=',
    ];

    public array $sortableFields = [
        'id', 'priority', 'due_date', 'created_at', 'updated_at',
    ];

    public function model(): string
    {
        return Task::class;
    }
}
