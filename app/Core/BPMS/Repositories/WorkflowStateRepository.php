<?php

declare(strict_types=1);

namespace App\Core\BPMS\Repositories;

use App\Contracts\Repository\BaseRepository;
use App\Core\BPMS\Entities\WorkflowState;

class WorkflowStateRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id'          => '=',
        'workflow_id' => '=',
        'name'        => 'like',
        'position'    => '=',
        'is_active'   => '=',
    ];

    public array $sortableFields = [
        'id', 'name', 'order', 'created_at', 'updated_at',
    ];

    public array $customFilters = [];

    public function model(): string
    {
        return WorkflowState::class;
    }
}
