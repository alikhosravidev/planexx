<?php

declare(strict_types=1);

namespace App\Core\Organization\Repositories;

use App\Contracts\Repository\BaseRepository;
use App\Core\Organization\Entities\Role;

class RoleRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id'    => '=',
        'name'  => 'like',
        'title' => 'like',
    ];

    public array $filterableFields = [
        'guard_name' => '=',
    ];

    public array $sortableFields = [
        'id',
        'name',
        'title',
        'created_at',
        'updated_at',
    ];

    public function model(): string
    {
        return Role::class;
    }
}
