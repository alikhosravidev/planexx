<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use App\Services\Transformer\FieldTransformers\DateTimeTransformer;

class PermissionTransformer extends BaseTransformer
{
    protected array $fieldTransformers = [
        'created_at' => DateTimeTransformer::class,
        'updated_at' => DateTimeTransformer::class,
    ];
}
