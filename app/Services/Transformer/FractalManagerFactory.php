<?php

declare(strict_types=1);

namespace App\Services\Transformer;

use App\Contracts\Transformer\SerializerInterface;
use Illuminate\Contracts\Container\Container;
use League\Fractal\Manager;

/**
 * Factory for creating pre-configured Fractal Manager instances.
 */
readonly class FractalManagerFactory
{
    public function __construct(
        private Container $container,
    ) {
    }

    /**
     * Create a Manager with specified serializer and includes/excludes.
     */
    public function create(SerializerInterface $serializer, array $includes = [], array $excludes = []): Manager
    {
        $manager = $this->container->make(Manager::class);

        // Extract internal Fractal serializer if it's our adapter
        $manager->setSerializer($serializer->getInternalSerializer());

        // Parse includes and excludes
        if (!empty($includes)) {
            $manager->parseIncludes($includes);
        }

        if (!empty($excludes)) {
            $manager->parseExcludes($excludes);
        }

        return $manager;
    }
}
