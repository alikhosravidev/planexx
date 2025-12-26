<?php

declare(strict_types=1);

/*
 * This file is part of the LSP API and Panels projects
 *
 * Copyright (c) >= 2023 LSP
 *
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 * Please follow OOP, SOLID and linux philosophy in development and becarefull about anti-patterns
 *
 * @CTO Mehrdad Dadkhah <dadkhah.ir@gmail.com>
 */

namespace App\Core\Organization\Http\Middlewares;

use App\Core\Organization\Entities\PersonalAccessToken;
use App\Core\Organization\Entities\User;
use App\Core\Organization\Services\AccessTokenService;
use App\Services\HttpRequestService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserAccessToken
{
    public function __construct(
        private readonly AccessTokenService $accessTokenService,
        private readonly HttpRequestService $requestService,
    ) {
    }

    public function handle(Request $request, Closure $next)
    {
        $user = $this->getAuthenticatedUser();

        if (null === $user) {
            $this->authWithCookie($request);
        }

        if ($user === null) {
            return $next($request);
        }

        $accessToken = PersonalAccessToken::findToken(
            $this->requestService->getTokenFromRequest()
        );

        if ($this->accessTokenService->isValid($accessToken, $user)) {
            return $next($request);
        }

        $this->accessTokenService->logout($user);

        return redirect()->route('login');
    }

    private function getAuthenticatedUser(): ?User
    {
        return Auth::user();
    }

    private function authWithCookie(Request $request): void
    {
        // Try to get token from:
        // 1. Cookie (for browser-based requests)
        // 2. Authorization header (for API requests)
        $tokenString = $request->cookie('token');

        if (! $tokenString) {
            return;
        }

        $token = PersonalAccessToken::findToken($tokenString);
        $user  = $token?->tokenable;

        if ($user === null) {
            return;
        }

        Auth::guard('web')->login($user);
    }
}
