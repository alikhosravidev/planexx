<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Contracts\Transformer\BaseFieldTransformer;
use Carbon\Carbon;
use Exception;

/**
 * Transformer for date/time fields.
 */
class DateTimeTransformer extends BaseFieldTransformer
{
    public function transform(mixed $value): mixed
    {
        if ($value instanceof Carbon) {
            return $value->format('Y-m-d H:i:s');
        }

        if (is_string($value)) {
            try {
                return Carbon::parse($value)->format('Y-m-d H:i:s');
            } catch (Exception $e) {
                return $value;
            }
        }

        return $value;
    }
}
