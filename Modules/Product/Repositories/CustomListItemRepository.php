<?php

declare(strict_types=1);

namespace Modules\Product\Repositories;

use App\Contracts\Repository\BaseRepository;
use Modules\Product\Entities\CustomListItem;

class CustomListItemRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id'             => '=',
        'reference_code' => 'like',
    ];

    public array $filterableFields = [
        'list_id'    => '=',
        'created_by' => '=',
    ];

    public array $sortableFields = [
        'id',
        'reference_code',
        'created_at',
    ];

    public function model(): string
    {
        return CustomListItem::class;
    }
}
