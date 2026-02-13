<?php

declare(strict_types=1);

namespace Modules\Product\Repositories;

use App\Contracts\Repository\BaseRepository;
use Modules\Product\Entities\CustomList;

class CustomListRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id'    => '=',
        'title' => 'like',
        'slug'  => 'like',
    ];

    public array $filterableFields = [
        'is_active'  => '=',
        'created_by' => '=',
    ];

    public array $sortableFields = [
        'id',
        'title',
        'created_at',
    ];

    public function model(): string
    {
        return CustomList::class;
    }
}
