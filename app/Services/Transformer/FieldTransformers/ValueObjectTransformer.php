<?php

declare(strict_types=1);

namespace App\Services\Transformer\FieldTransformers;

use App\Contracts\Transformer\FieldTransformerInterface;

class ValueObjectTransformer implements FieldTransformerInterface
{
    public function transform($valueObject): mixed
    {
        if (!is_object($valueObject)) {
            return $valueObject;
        }

        if (method_exists($valueObject, '__toString')) {
            return (string) $valueObject;
        }

        if (property_exists($valueObject, 'value')) {
            return $valueObject->value;
        }

        return $valueObject;
    }

    public function transformMany(array $valueObjects): array
    {
        return array_map(fn ($vo) => $this->transform($vo), $valueObjects);
    }
}
