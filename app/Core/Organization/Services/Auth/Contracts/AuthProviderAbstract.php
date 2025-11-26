<?php

declare(strict_types=1);

namespace App\Core\Organization\Services\Auth\Contracts;

use App\Contracts\User\UserRepositoryInterface;
use App\Core\Organization\Entities\User;
use App\Core\Organization\events\UserRegistered;
use App\Core\Organization\Services\AccessTokenService;
use App\Core\Organization\Services\Auth\DTOs\AuthConfig;
use App\Core\Organization\Services\Auth\DTOs\AuthToken;
use App\Core\Organization\Services\Auth\DTOs\ClientMetadataDto;
use App\Core\Organization\Services\Auth\Exceptions\AuthException;
use App\Core\Organization\Services\Auth\ValueObjects\Identifier;
use App\Services\HttpRequestService;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;

abstract class AuthProviderAbstract
{
    protected readonly Guard|StatefulGuard $author;

    public function __construct(
        protected readonly AccessTokenService $accessToken,
        protected readonly UserRepositoryInterface $userRepository,
        protected readonly HttpRequestService $requestService,
        protected readonly AuthConfig $authConfig,
    ) {
        $this->author = Auth::guard($authConfig->authGuard);
    }

    public function logout(?string $token = null): bool
    {
        if (! $this->author->check()) {
            return false;
        }

        if ($token !== null) {
            $this->accessToken->withAccessToken(
                $this->author->user(),
                $token
            );
        }

        // logout current access token
        return $this->accessToken->logout(
            $this->author->user()
        );
    }

    protected function loginUser(User $user, ClientMetadataDto $clientMetadataDto): AuthToken
    {
        $loginLimitationCount = $this->accessToken->getLoginLimitationCount();
        $isLoginLimitExceeded = $this->accessToken->isLoginLimitExceeded(
            user: $user,
            loginLimitationCount: $loginLimitationCount,
            fingerprint: $clientMetadataDto->fingerprint,
        );

        if ($isLoginLimitExceeded) {
            throw AuthException::concurrentLoginLimit($loginLimitationCount);
        }

        $token = $user->createToken(
            name: $this->authConfig->authTokenName,
            userIp: $clientMetadataDto->ipAddress,
            fingerprint: $clientMetadataDto->fingerprint,
            userAgent: $clientMetadataDto->userAgent,
        );

        Auth::login($user);
        $user->updateLastLogin()->save();

        return new AuthToken(
            token: $token->plainTextToken,
            type : 'Bearer'
        );
    }

    protected function registerUser(Identifier $identifier, ?string $password = null): User
    {
        $user = $this->userRepository->registerWithIdentifier($identifier, $password);
        event(new UserRegistered($user));

        return $user;
    }
}
