<?php

declare(strict_types=1);

namespace App\Services\Transformer;

use League\Fractal\Serializer\ArraySerializer;

class CustomArraySerializer extends ArraySerializer
{
    public function collection(?string $resourceKey, array $data): array
    {
        return $data;
    }

    public function item(?string $resourceKey, array $data): array
    {
        return $data;
    }
}
