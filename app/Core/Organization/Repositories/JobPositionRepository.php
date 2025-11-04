<?php

declare(strict_types=1);

namespace App\Core\Organization\Repositories;

use App\Contracts\Repository\BaseRepository;
use App\Core\Organization\Entities\JobPosition;

class JobPositionRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id'        => '=',
        'title'     => 'like',
        'code'      => 'like',
        'tier'      => '=',
        'is_active' => '=',
    ];

    public array $sortableFields = [
        'id',
        'title',
        'code',
        'created_at',
        'updated_at',
    ];

    public function model(): string
    {
        return JobPosition::class;
    }
}
