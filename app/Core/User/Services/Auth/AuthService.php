<?php

declare(strict_types=1);

namespace App\Core\User\Services\Auth;

use App\Core\User\Services\Auth\Contracts\AuthHandlerInterface;
use App\Core\User\Services\Auth\Contracts\PasswordResetable;
use App\Core\User\Services\Auth\DTOs\AuthRequestDto;
use App\Core\User\Services\Auth\DTOs\AuthResponse;
use App\Core\User\Services\Auth\DTOs\ChangePasswordRequestDto;
use App\Core\User\Services\Auth\DTOs\ResetPasswordRequestDto;
use App\Core\User\Services\Auth\ValueObjects\Identifier;

class AuthService
{
    public function __construct(
        private readonly ProviderResolver $providerResolver
    ) {
    }

    public function init(Identifier $identifier, ?string $authType = null): AuthResponse
    {
        return $this->getProvider($identifier, $authType)->init($identifier);
    }

    /**
     * login or register user.
     */
    public function auth(AuthRequestDto $requestData): AuthResponse
    {
        return $this->getProvider($requestData->identifier, $requestData->authType)->auth($requestData);
    }

    public function logout(?string $token = null): bool
    {
        return $this->getProvider(null)->logout($token);
    }

    public function changePassword(ChangePasswordRequestDto $requestData): AuthResponse
    {
        return $this->getProvider($requestData->identifier)->changePassword($requestData);
    }

    public function initResetPassword(Identifier $identifier): AuthResponse
    {
        $provider = $this->getProvider($identifier, 'otp');

        if (! $provider instanceof PasswordResetable) {
            throw new \LogicException('The selected provider does not support password reset.');
        }

        return $provider->initResetPassword($identifier);
    }

    public function resetPassword(ResetPasswordRequestDto $requestData): AuthResponse
    {
        $provider = $this->getProvider($requestData->identifier, 'otp');

        if (! $provider instanceof PasswordResetable) {
            throw new \LogicException('The selected provider does not support password reset.');
        }

        return $provider->resetPassword($requestData);
    }

    private function getProvider(?Identifier $identifier, ?string $authType = null): AuthHandlerInterface
    {
        return $this->providerResolver->resolve($identifier, $authType);
    }
}
