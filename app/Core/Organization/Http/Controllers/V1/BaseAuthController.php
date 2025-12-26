<?php

namespace App\Core\Organization\Http\Controllers\V1;

use App\Contracts\Controller\BaseAPIController;
use App\Contracts\Repository\BaseRepository;
use App\Contracts\Transformer\BaseTransformer;
use App\Core\Organization\Http\Requests\V1\Admin\AuthInitiateRequest;
use App\Core\Organization\Http\Requests\V1\Admin\AuthRequest;
use App\Core\Organization\Http\Requests\V1\Admin\InitiateResetPasswordRequest;
use App\Core\Organization\Http\Requests\V1\Admin\ResetPasswordRequest;
use App\Core\Organization\Services\Auth\AuthService;
use App\Core\Organization\Services\Auth\DTOs\AuthRequestDto;
use App\Core\Organization\Services\Auth\DTOs\ClientMetadataDto;
use App\Core\Organization\Services\Auth\DTOs\ResetPasswordRequestDto;
use App\Core\Organization\Services\Auth\ValueObjects\Identifier;
use App\Services\HttpRequestService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

abstract class BaseAuthController extends BaseAPIController
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly HttpRequestService $requestService,
        BaseRepository  $repository,
        BaseTransformer $transformer,
    ) {
        parent::__construct($repository, $transformer);
    }

    public function initiateAuth(AuthInitiateRequest $request): JsonResponse
    {
        $identifier   = Identifier::fromString($request->get('identifier'));
        $authResponse = $this->authService->init($identifier, $request->get('authType'));

        return $this->response->success(
            $this->transformer->transform($authResponse),
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
            $this->transformer->transform($authResponse),
            $authResponse->message
        );
    }

    public function initiateResetPassword(InitiateResetPasswordRequest $request): JsonResponse
    {
        $identifier   = Identifier::fromString($request->get('identifier'));
        $authResponse = $this->authService->initResetPassword($identifier);

        return $this->response->success(
            $this->transformer->transform($authResponse),
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
            $this->transformer->transform($authResponse),
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
                trans('Organization::errors.logout_not_possible'),
                SymfonyResponse::HTTP_NOT_MODIFIED
            );
        }

        return $this->response->success(
            [],
            trans('Organization::success.logout')
        );
    }

}
