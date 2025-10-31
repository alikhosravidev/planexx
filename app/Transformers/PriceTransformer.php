<?php

declare(strict_types=1);

namespace App\Transformers;

/**
 * Transformer for price fields.
 */
class PriceTransformer extends \App\Contracts\Transformer\BaseFieldTransformer
{
    public function transform(mixed $value): mixed
    {
        if (!is_numeric($value)) {
            return $value;
        }

        return number_format((float) $value, 2, '.', ',') . ' USD';
    }
}
