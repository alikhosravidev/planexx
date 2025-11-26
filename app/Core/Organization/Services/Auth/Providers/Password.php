<?php

declare(strict_types=1);

namespace App\Core\Organization\Services\Auth\Providers;

use App\Core\Organization\Services\Auth\Contracts\AuthHandlerInterface;
use App\Core\Organization\Services\Auth\Contracts\AuthProviderAbstract;
use App\Core\Organization\Services\Auth\DTOs\AuthRequestDto;
use App\Core\Organization\Services\Auth\DTOs\AuthResponse;
use App\Core\Organization\Services\Auth\DTOs\AuthToken;
use App\Core\Organization\Services\Auth\DTOs\ChangePasswordRequestDto;
use App\Core\Organization\Services\Auth\Exceptions\AuthException;
use App\Core\Organization\Services\Auth\ValueObjects\Identifier;
use Illuminate\Support\Facades\Hash;

final class Password extends AuthProviderAbstract implements AuthHandlerInterface
{
    public function supports(?Identifier $identifier, ?string $authType): bool
    {
        if ($authType === 'otp' || empty($identifier)) {
            return false;
        }

        if ($identifier->type->isUsername()) {
            return true;
        }

        $user = $this->userRepository->findWithIdentifier($identifier);

        return ! empty($user?->password);
    }

    public function init(Identifier $identifier): AuthResponse
    {
        return new AuthResponse(
            message: trans('Organization::success.password_required_to_continue'),
            identifier: $identifier,
            nextStep: 'password',
        );
    }

    public function auth(AuthRequestDto $requestData): AuthResponse
    {
        $user = $this->userRepository->findWithIdentifier($requestData->identifier);

        if ($user === null) {
            throw AuthException::credentialsInvalid();
        }

        if (! Hash::check($requestData->password, $user->password)) {
            throw AuthException::passwordIncorrect();
        }

        $token = $this->loginUser($user, $requestData->clientMetadata);

        return new AuthResponse(
            message     : trans('Organization::success.welcome_message'),
            identifier: $requestData->identifier,
            token       : $token,
            user        : $user,
        );
    }

    public function changePassword(ChangePasswordRequestDto $requestData): AuthResponse
    {
        $user = $this->userRepository->findWithIdentifier($requestData->identifier);

        if (! $user) {
            throw AuthException::userNotRegistered();
        }

        if (! Hash::check($requestData->password, $user->password)) {
            throw AuthException::passwordIncorrect();
        }

        $user->changePassword($requestData->newPassword)->save();

        return new AuthResponse(
            message: trans('Organization::success.password_changed'),
            identifier: $requestData->identifier,
            token  : new AuthToken($this->requestService->getTokenFromRequest(), 'Bearer'),
            user   : $user,
        );
    }
}
