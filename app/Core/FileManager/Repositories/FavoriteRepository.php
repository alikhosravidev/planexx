<?php

declare(strict_types=1);

namespace App\Core\FileManager\Repositories;

use App\Contracts\Repository\BaseRepository;
use App\Core\FileManager\Entities\Favorite;

class FavoriteRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id'          => '=',
        'user_id'     => '=',
        'entity_type' => '=',
        'entity_id'   => '=',
    ];

    public array $sortableFields = [
        'id',
        'created_at',
    ];

    public function model(): string
    {
        return Favorite::class;
    }
}
