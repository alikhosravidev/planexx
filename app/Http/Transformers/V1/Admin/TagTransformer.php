<?php

declare(strict_types=1);

namespace App\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use App\Services\Transformer\FieldTransformers\DateTimeTransformer;

class TagTransformer extends BaseTransformer
{
    protected array $fieldTransformers = [
        'created_at' => DateTimeTransformer::class,
        'updated_at' => DateTimeTransformer::class,
    ];

    protected array $availableIncludes = [];
}
