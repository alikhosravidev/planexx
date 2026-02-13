<?php

declare(strict_types=1);

namespace Modules\Product\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\Organization\Http\Transformers\V1\Admin\UserTransformer;
use Modules\Product\Entities\CustomListItem;

class CustomListItemTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'values',
        'creator',
    ];

    public function includeValues(CustomListItem $item)
    {
        return $this->collectionRelation(
            model: $item,
            relationName: 'values',
            transformer: CustomListValueTransformer::class,
        );
    }

    public function includeCreator(CustomListItem $item)
    {
        return $this->itemRelation(
            model: $item,
            relationName: 'creator',
            transformer: UserTransformer::class,
            foreignKey: 'created_by',
        );
    }
}
