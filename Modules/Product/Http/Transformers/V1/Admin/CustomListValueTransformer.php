<?php

declare(strict_types=1);

namespace Modules\Product\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use Modules\Product\Entities\CustomListValue;

class CustomListValueTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'attribute',
    ];

    protected function getVirtualFieldResolvers(): array
    {
        return [
            'resolved_value' => fn (CustomListValue $value) => $value->value_text
                ?? $value->value_number
                ?? $value->value_date?->toDateString()
                ?? $value->value_boolean,
        ];
    }

    public function includeAttribute(CustomListValue $value)
    {
        return $this->itemRelation(
            model: $value,
            relationName: 'attribute',
            transformer: CustomListAttributeTransformer::class,
            foreignKey: 'attribute_id',
        );
    }
}
