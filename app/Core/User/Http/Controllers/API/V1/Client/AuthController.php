<?php

declare(strict_types=1);

namespace App\Core\User\Http\Controllers\API\V1\Client;

use App\Contracts\Controller\APIBaseController;
use App\Contracts\User\UserRepositoryInterface;
use App\Core\User\Http\Requests\API\V1\Client\AuthInitiateRequest;
use App\Core\User\Http\Requests\API\V1\Client\AuthRequest;
use App\Core\User\Http\Requests\API\V1\Client\InitiateResetPasswordRequest;
use App\Core\User\Http\Requests\API\V1\Client\ResetPasswordRequest;
use App\Core\User\Http\Transformers\V1\Client\AuthTransformer;
use App\Core\User\Services\Auth\AuthService;
use App\Core\User\Services\Auth\DTOs\AuthRequestDto;
use App\Core\User\Services\Auth\DTOs\ClientMetadataDto;
use App\Core\User\Services\Auth\DTOs\ResetPasswordRequestDto;
use App\Core\User\Services\Auth\ValueObjects\Identifier;
use App\Services\HttpRequestService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class AuthController extends APIBaseController
{
    public function __construct(
        UserRepositoryInterface $userRepository,
        AuthTransformer $authTransformer,
        private readonly AuthService $authService,
        private readonly HttpRequestService $requestService,
    ) {
        parent::__construct($userRepository, $authTransformer);
    }

    public function initiateAuth(AuthInitiateRequest $request): JsonResponse
    {
        $identifier   = Identifier::fromString($request->get('identifier'));
        $authResponse = $this->authService->init($identifier, $request->get('authType'));

        return $this->response->success(
            $this->transformer->transformOne($authResponse)->toArray(),
            $authResponse->message
        );
    }

    public function auth(AuthRequest $request): JsonResponse
    {
        $identifier   = Identifier::fromString($request->get('identifier'));
        $authResponse = $this->authService->auth(
            new AuthRequestDto(
                identifier    : $identifier,
                password      : (string) $request->get('password'),
                clientMetadata: new ClientMetadataDto(
                    ipAddress  : (string) $request->ip(),
                    userAgent  : $request->userAgent(),
                    fingerprint: $request->get('fingerprint'),
                ),
                authType      : $request->get('authType'),
            )
        );

        return $this->response->success(
            $this->transformer->transformOne($authResponse)->toArray(),
            $authResponse->message
        );
    }

    public function initiateResetPassword(InitiateResetPasswordRequest $request): JsonResponse
    {
        $identifier   = Identifier::fromString($request->get('identifier'));
        $authResponse = $this->authService->initResetPassword($identifier);

        return $this->response->success(
            $this->transformer->transformOne($authResponse)->toArray(),
            $authResponse->message
        );
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $identifier   = Identifier::fromString($request->get('identifier'));
        $authResponse = $this->authService->resetPassword(
            new ResetPasswordRequestDto(
                identifier    : $identifier,
                code          : (string) $request->get('code'),
                password      : (string) $request->get('password'),
                repeatPassword: (string) $request->get('repeat_password'),
                clientMetadata: new ClientMetadataDto(
                    ipAddress  : (string) $request->ip(),
                    userAgent  : $request->userAgent(),
                    fingerprint: $request->get('fingerprint'),
                ),
            )
        );

        return $this->response->success(
            $this->transformer->transformOne($authResponse)->toArray(),
            $authResponse->message
        );
    }

    public function logout(): JsonResponse
    {
        $result = $this->authService->logout(
            $this->requestService->getTokenFromRequest()
        );

        if (! $result) {
            return $this->response->failed(
                trans('user::errors.logout_not_possible'),
                SymfonyResponse::HTTP_NOT_MODIFIED
            );
        }

        return $this->response->success(
            [],
            trans('user::success.logout')
        );
    }
}
