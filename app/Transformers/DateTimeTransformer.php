<?php

declare(strict_types=1);

namespace App\Contracts\Transformer;

/**
 * Transformer for date/time fields.
 */
class DateTimeTransformer extends BaseFieldTransformer
{
    public function transform(mixed $value): mixed
    {
        if ($value instanceof \Carbon\Carbon) {
            return $value->format('Y-m-d H:i:s');
        }

        if (is_string($value)) {
            try {
                return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                return $value;
            }
        }

        return $value;
    }
}
