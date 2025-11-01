<?php

namespace App\Core\User\Repositories;

use App\Contracts\Repository\BaseRepository;
use App\Core\User\Entities\Province;

class ProvinceRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id' => '=',
        'name' => 'like',
    ];

    public array $sortableFields = [
        'id',
        'name',
        'created_at',
        'updated_at',
    ];

    public function model(): string
    {
        return Province::class;
    }
}
