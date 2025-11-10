<?php

declare(strict_types=1);

namespace App\Core\BPMS\Repositories;

use App\Contracts\Repository\BaseRepository;
use App\Core\BPMS\Entities\FollowUp;

class FollowUpRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id'              => '=',
        'task_id'         => '=',
        'type'            => '=',
        'created_by'      => '=',
        'new_assignee_id' => '=',
        'new_state_id'    => '=',
    ];

    public array $sortableFields = [
        'id', 'type', 'created_at',
    ];

    public function model(): string
    {
        return FollowUp::class;
    }
}
