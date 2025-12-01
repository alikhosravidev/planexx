<?php

declare(strict_types=1);

namespace App\Core\Organization\Repositories;

use App\Contracts\Repository\BaseRepository;
use App\Core\Organization\Entities\Department;

class DepartmentRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id'   => '=',
        'name' => 'like',
        'code' => 'like',
    ];

    public array $filterableFields = [
        'manager_id' => '=',
        'is_active'  => '=',
        'parent_id'  => '=',
    ];

    public array $sortableFields = [
        'id',
        'name',
        'code',
        'created_at',
        'updated_at',
    ];

    public function model(): string
    {
        return Department::class;
    }
}
