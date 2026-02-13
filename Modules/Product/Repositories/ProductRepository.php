<?php

declare(strict_types=1);

namespace Modules\Product\Repositories;

use App\Contracts\Repository\BaseRepository;
use Modules\Product\Entities\Product;

class ProductRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id'    => '=',
        'title' => 'like',
        'slug'  => 'like',
    ];

    public array $filterableFields = [
        'status'      => '=',
        'is_featured' => '=',
        'created_by'  => '=',
    ];

    public array $sortableFields = [
        'id',
        'title',
        'price',
        'status',
        'created_at',
        'updated_at',
    ];

    public function model(): string
    {
        return Product::class;
    }
}
