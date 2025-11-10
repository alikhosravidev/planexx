<?php

declare(strict_types=1);

namespace App\Core\BPMS\Repositories;

use App\Contracts\Repository\BaseRepository;
use App\Core\BPMS\Entities\Workflow;

class WorkflowRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id'                => '=',
        'name'              => 'like',
        'slug'              => 'like',
        'department_id'     => '=',
        'workflow_owner_id' => '=',
        'created_by'        => '=',
        'is_active'         => '=',
    ];

    public array $sortableFields = [
        'id', 'name', 'slug', 'created_at', 'updated_at',
    ];

    public function model(): string
    {
        return Workflow::class;
    }
}
