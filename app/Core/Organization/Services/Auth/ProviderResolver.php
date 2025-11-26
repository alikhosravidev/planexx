<?php

declare(strict_types=1);

namespace App\Core\Organization\Services\Auth;

use App\Core\Organization\Services\Auth\Contracts\AuthHandlerInterface;
use App\Core\Organization\Services\Auth\ValueObjects\Identifier;
use LogicException;

final class ProviderResolver
{
    /**
     * @param  iterable<AuthHandlerInterface>  $providers
     */
    public function __construct(
        private readonly iterable $providers
    ) {
    }

    public function resolve(?Identifier $identifier, ?string $authType): AuthHandlerInterface
    {
        foreach ($this->providers as $provider) {
            if ($provider->supports($identifier, $authType)) {
                return $provider;
            }
        }

        throw new LogicException('No suitable authentication provider found for the given request.');
    }
}
