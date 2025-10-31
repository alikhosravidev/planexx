<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Contracts\Transformer\BaseFieldTransformer;
use BackedEnum;

/**
 * Transformer for enums.
 */
class EnumTransformer extends BaseFieldTransformer
{
    public function transform(mixed $value): mixed
    {
        if ($value instanceof BackedEnum) {
            return [
                'key'   => $value->name,
                'value' => $value->value,
            ];
        }

        return $value;
    }
}
