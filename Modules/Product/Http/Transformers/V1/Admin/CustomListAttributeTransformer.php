<?php

declare(strict_types=1);

namespace Modules\Product\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use App\Services\Transformer\FieldTransformers\EnumTransformer;

class CustomListAttributeTransformer extends BaseTransformer
{
    protected array $fieldTransformers = [
        'data_type' => EnumTransformer::class,
    ];
}
