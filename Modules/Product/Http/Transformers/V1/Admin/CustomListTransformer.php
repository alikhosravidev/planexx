<?php

declare(strict_types=1);

namespace Modules\Product\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use Modules\Product\Entities\CustomList;

class CustomListTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'attributes',
        'creator',
    ];

    protected function getVirtualFieldResolvers(): array
    {
        return [
            'items_count' => fn (CustomList $list) => $list->items_count ?? $list->items()->count(),
        ];
    }

    public function includeAttributes(CustomList $list)
    {
        return $this->collectionRelation(
            model: $list,
            relationName: 'attributes',
            transformer: CustomListAttributeTransformer::class,
        );
    }

    public function includeCreator(CustomList $list)
    {
        return $this->itemRelation(
            model: $list,
            relationName: 'creator',
            transformer: \App\Core\Organization\Http\Transformers\V1\Admin\UserTransformer::class,
            foreignKey: 'created_by',
        );
    }
}
