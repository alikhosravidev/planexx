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

namespace App\Core\Organization\Services;

use App\Core\Organization\Entities\PersonalAccessToken;
use App\Core\Organization\Entities\User;
use App\Services\HttpRequestService;
use App\Utilities\Cookie;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AccessTokenService
{
    public function __construct(
        private readonly Request $request,
        private readonly HttpRequestService $requestService,
    ) {
    }

    public function isLoginLimitExceeded(
        User $user,
        int $loginLimitationCount,
        ?string $fingerprint = null
    ): bool {
        if ($loginLimitationCount <= 0) {
            return false;
        }

        $accessTokenCount = PersonalAccessToken::query()
            ->selectRaw('count(distinct coalesce(fingerprint, id)) as total_count')
            ->whereNull('logout_at')
            ->where('tokenable_type', '=', $user->getMorphClass())
            ->where('tokenable_id', '=', $user->id)
            ->when($fingerprint !== null, static function (Builder $query) use ($fingerprint): void {
                $query->where('fingerprint', '!=', $fingerprint);
            })
            ->first()
            ?->total_count ?? 0;

        return $accessTokenCount >= $loginLimitationCount;
    }

    public function getLoginLimitationCount(): int
    {
        return (int) config('services.auth.login_limitation_count', 0);
    }

    public function logout(User $user): bool
    {
        $accessToken = $user->findCurrentAccessToken();

        if ($accessToken !== null && $accessToken->logout_at === null) {
            $accessToken->update(
                [
                    'logout_at' => now(),
                ]
            );
        }

        if ($this->requestService->isWebRequest() && $this->request->session()) {
            Cookie::deleteFromAllDomains('token');
            $this->request->session()->invalidate();
        }

        return true;
    }

    public function withAccessToken(User $user, string $token): static
    {
        $user->withAccessToken($token);

        return $this;
    }

    public function isValid(?PersonalAccessToken $accessToken, User $user): bool
    {
        if ($accessToken === null || $accessToken->logout_at !== null) {
            return false;
        }

        if ($this->isExpired($accessToken)) {
            return false;
        }

        return $this->belongsToUser($accessToken, $user);
    }

    private function isExpired(PersonalAccessToken $accessToken): bool
    {
        return $accessToken->expires_at !== null && $accessToken->expires_at->isPast();
    }

    private function belongsToUser(PersonalAccessToken $accessToken, User $user): bool
    {
        return $accessToken->tokenable_type === $user->getMorphClass()
            && $accessToken->tokenable_id   === $user->id
            && $accessToken->name           === config('sanctum.token_name');
    }
}
