<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\QueryInterface;
use App\Exceptions\ServiceResolutionException;

class QueryServiceLocator
{
    private array $services = [];
    public function register(string $contextName, string $serviceInterface): void
    {
        if (! interface_exists($serviceInterface)) {
            throw new ServiceResolutionException(
                sprintf('Interface [%s] does not exist', $serviceInterface)
            );
        }

        if (! is_subclass_of($serviceInterface, QueryInterface::class)) {
            throw new ServiceResolutionException(
                sprintf('[%s] must implement QueryServiceInterface', $serviceInterface)
            );
        }

        $this->services[$contextName] = $serviceInterface;
    }

    public function resolve(string $contextName): QueryInterface
    {
        if (! isset($this->services[$contextName])) {
            throw new ServiceResolutionException(
                sprintf('No query service registered for context: %s', $contextName)
            );
        }

        return app($this->services[$contextName]);
    }

    public function has(string $contextName): bool
    {
        return isset($this->services[$contextName]);
    }

    public function getRegisteredContexts(): array
    {
        return array_keys($this->services);
    }
}
