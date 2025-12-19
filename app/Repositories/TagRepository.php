<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repository\BaseRepository;
use App\Entities\Tag;

class TagRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id'   => '=',
        'name' => 'like',
        'slug' => 'like',
    ];

    public array $sortableFields = [
        'id',
        'name',
        'slug',
        'usage_count',
        'created_at',
        'updated_at',
    ];

    public function model(): string
    {
        return Tag::class;
    }
}
