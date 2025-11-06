<?php

declare(strict_types=1);

namespace App\Contracts\Transformer;

/**
 * Interface for data serialization.
 */
interface SerializerInterface
{
    /**
     * Serialize data to array format.
     *
     * @param array $data
     * @return array
     */
    public function serialize(array $data): array;
}
