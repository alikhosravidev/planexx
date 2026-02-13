<?php

declare(strict_types=1);

namespace Modules\Product\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use Modules\Product\Entities\Category;

class CategoryTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'parent',
        'children',
    ];

    public function includeParent(Category $category)
    {
        return $this->itemRelation(
            model: $category,
            relationName: 'parent',
            transformer: self::class,
            foreignKey: 'parent_id',
        );
    }

    public function includeChildren(Category $category)
    {
        return $this->collectionRelation(
            model: $category,
            relationName: 'children',
            transformer: self::class,
        );
    }
}
