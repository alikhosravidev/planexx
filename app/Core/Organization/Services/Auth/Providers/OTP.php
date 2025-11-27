<?php

declare(strict_types=1);

namespace App\Core\Organization\Services\Auth\Providers;

use App\Core\Organization\Repositories\UserRepository;
use App\Core\Organization\Services\AccessTokenService;
use App\Core\Organization\Services\Auth\Contracts\AuthHandlerInterface;
use App\Core\Organization\Services\Auth\Contracts\AuthProviderAbstract;
use App\Core\Organization\Services\Auth\Contracts\PasswordResetable;
use App\Core\Organization\Services\Auth\DTOs\AuthConfig;
use App\Core\Organization\Services\Auth\DTOs\AuthRequestDto;
use App\Core\Organization\Services\Auth\DTOs\AuthResponse;
use App\Core\Organization\Services\Auth\DTOs\AuthToken;
use App\Core\Organization\Services\Auth\DTOs\ChangePasswordRequestDto;
use App\Core\Organization\Services\Auth\DTOs\ResetPasswordRequestDto;
use App\Core\Organization\Services\Auth\Exceptions\AuthException;
use App\Core\Organization\Services\Auth\ValueObjects\Identifier;
use App\Core\Organization\Services\OTPService\Exceptions\OTPException;
use App\Core\Organization\Services\OTPService\OTPService;
use App\Services\HttpRequestService;
use App\Utilities\StringUtility;

final class OTP extends AuthProviderAbstract implements AuthHandlerInterface, PasswordResetable
{
    public function __construct(
        private readonly OTPService $otpService,
        AccessTokenService $accessToken,
        UserRepository $userRepository,
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
            message: trans("Organization::success.channel_messages.{$response->channel}"),
            identifier: $identifier,
            nextStep: 'otp',
            otpData: $response,
        );
    }

    public function auth(AuthRequestDto $requestData): AuthResponse
    {
        if (! $this->otpService->check($requestData->identifier, $requestData->password)) {
            throw OTPException::incorrect();
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
            message     : trans('Organization::success.welcome_message'),
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
            throw OTPException::incorrect();
        }

        $user = $this->userRepository->findWithIdentifier($requestData->identifier);

        if (! $user) {
            throw AuthException::userNotRegistered();
        }

        $user->changePassword($requestData->newPassword)
            ->verifyIdentifier($requestData->identifier)
            ->save();

        return new AuthResponse(
            message: trans('Organization::success.password_changed'),
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
            message: trans("Organization::success.channel_messages.{$response->channel->value}"),
            identifier: $identifier,
            nextStep: 'reset_password',
            otpData: $response,
        );
    }

    public function resetPassword(ResetPasswordRequestDto $requestData): AuthResponse
    {
        if (! $this->otpService->check($requestData->identifier, $requestData->code)) {
            throw OTPException::incorrect();
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
            message     : trans('Organization::success.password_reset'),
            identifier  : $requestData->identifier,
            token       : $token,
            user        : $user,
        );
    }
}
