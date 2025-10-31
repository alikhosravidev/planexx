<?php

declare(strict_types=1);

namespace App\Core\User\Services\Auth\Providers;

use App\Contracts\User\UserRepositoryInterface;
use App\Core\User\Services\AccessTokenService;
use App\Core\User\Services\Auth\Contracts\AuthHandlerInterface;
use App\Core\User\Services\Auth\Contracts\AuthProviderAbstract;
use App\Core\User\Services\Auth\Contracts\PasswordResetable;
use App\Core\User\Services\Auth\DTOs\AuthConfig;
use App\Core\User\Services\Auth\DTOs\AuthRequestDto;
use App\Core\User\Services\Auth\DTOs\AuthResponse;
use App\Core\User\Services\Auth\DTOs\AuthToken;
use App\Core\User\Services\Auth\DTOs\ChangePasswordRequestDto;
use App\Core\User\Services\Auth\DTOs\ResetPasswordRequestDto;
use App\Core\User\Services\Auth\Exceptions\AuthException;
use App\Core\User\Services\Auth\ValueObjects\Identifier;
use App\Core\User\Services\OTPService\OTPService;
use App\Services\HttpRequestService;
use App\Utilities\StringUtility;

final class OTP extends AuthProviderAbstract implements AuthHandlerInterface, PasswordResetable
{
    public function __construct(
        private readonly OTPService $otpService,
        AccessTokenService $accessToken,
        UserRepositoryInterface $userRepository,
        HttpRequestService $requestService,
        AuthConfig $authConfig,
    ) {
        parent::__construct(
            accessToken: $accessToken,
            userRepository: $userRepository,
            requestService: $requestService,
            authConfig: $authConfig,
        );
    }

    public function supports(?Identifier $identifier, ?string $authType): bool
    {
        if ($authType === 'otp' || empty($identifier)) {
            return true;
        }

        if ($identifier->type->isUsername()) {
            return false;
        }

        $user = $this->userRepository->findWithIdentifier($identifier);

        return empty($user?->password);
    }

    public function init(Identifier $identifier): AuthResponse
    {
        $user     = $this->userRepository->findWithIdentifier($identifier);
        $response = $this->otpService->send($identifier, $user);

        return new AuthResponse(
            message: trans("user::success.channel_messages.{$response->channel->value}"),
            identifier: $identifier,
            nextStep: 'otp',
            otpData: $response,
        );
    }

    public function auth(AuthRequestDto $requestData): AuthResponse
    {
        if (! $this->otpService->check($requestData->identifier, $requestData->password)) {
            throw AuthException::credentialsInvalid();
        }

        $user         = $this->userRepository->findWithIdentifier($requestData->identifier);
        $isRegistered = false;

        if ($user === null) {
            $user         = $this->registerUser($requestData->identifier);
            $isRegistered = true;
        }

        $user->verifyIdentifier($requestData->identifier)->save();
        $token = $this->loginUser($user, $requestData->clientMetadata);

        return new AuthResponse(
            message     : trans('user::success.welcome_message'),
            identifier  : $requestData->identifier,
            token       : $token,
            user        : $user,
            isRegistered: $isRegistered,
        );
    }

    public function changePassword(ChangePasswordRequestDto $requestData): AuthResponse
    {
        $pass = StringUtility::numberToEn($requestData->password);

        if (! $this->otpService->check($requestData->identifier, $pass)) {
            throw AuthException::credentialsInvalid();
        }

        $user = $this->userRepository->findWithIdentifier($requestData->identifier);

        if (! $user) {
            throw AuthException::userNotRegistered();
        }

        $user->changePassword($requestData->newPassword)
            ->verifyIdentifier($requestData->identifier)
            ->save();

        return new AuthResponse(
            message: trans('user::success.password_changed'),
            identifier: $requestData->identifier,
            token: new AuthToken($this->requestService->getTokenFromRequest(), 'Bearer'),
            user: $user,
        );
    }

    public function initResetPassword(Identifier $identifier): AuthResponse
    {
        $user = $this->userRepository->findWithIdentifier($identifier);

        if (! $user) {
            throw AuthException::userNotRegistered();
        }
        $response = $this->otpService->send($identifier, $user);

        return new AuthResponse(
            message: trans("user::success.channel_messages.{$response->channel->value}"),
            identifier: $identifier,
            nextStep: 'reset_password',
            otpData: $response,
        );
    }

    public function resetPassword(ResetPasswordRequestDto $requestData): AuthResponse
    {
        if (! $this->otpService->check($requestData->identifier, $requestData->code)) {
            throw AuthException::credentialsInvalid();
        }

        if ($requestData->password !== $requestData->repeatPassword) {
            throw AuthException::invalidState();
        }

        $user = $this->userRepository->findWithIdentifier($requestData->identifier);
        $user->changePassword($requestData->password)
            ->verifyIdentifier($requestData->identifier)
            ->save();

        $token = $this->loginUser($user, $requestData->clientMetadata);

        return new AuthResponse(
            message     : trans('user::success.password_reset'),
            identifier  : $requestData->identifier,
            token       : $token,
            user        : $user,
        );
    }
}
