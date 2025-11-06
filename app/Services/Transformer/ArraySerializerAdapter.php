<?php

declare(strict_types=1);

namespace App\Services\Transformer;

use App\Contracts\Transformer\SerializerInterface;
use League\Fractal\Serializer\ArraySerializer;

/**
 * Adapter for Fractal's ArraySerializer implementing our SerializerInterface.
 */
readonly class ArraySerializerAdapter implements SerializerInterface
{
    public function __construct(
        private ArraySerializer $serializer,
    ) {
    }

    /**
     * Serialize data to array format.
     */
    public function serialize(array $data): array
    {
        return $this->serializer->collection(null, $data);
    }

    /**
     * Get the internal Fractal serializer.
     */
    public function getInternalSerializer(): ArraySerializer
    {
        return $this->serializer;
    }
}
