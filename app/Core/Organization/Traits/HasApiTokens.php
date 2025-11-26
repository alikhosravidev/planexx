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

namespace App\Core\Organization\Traits;

use App\Core\Organization\Entities\PersonalAccessToken;
use App\Services\HttpRequestService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Sanctum\HasApiTokens as HasApiTokensTrait;
use Laravel\Sanctum\NewAccessToken;

trait HasApiTokens
{
    use HasApiTokensTrait {
        tokens as public allTokens;
    }

    public function tokens(): MorphMany
    {
        return $this->allTokens()
            ->whereNull('logout_at')
            ->where(static function (Builder $query): void {
                $query
                    ->whereNull('expires_at')
                    ->orWhereDate('expires_at', '<=', now());
            });
    }

    public function findCurrentAccessToken(): ?PersonalAccessToken
    {
        return PersonalAccessToken::findToken(
            resolve(HttpRequestService::class)->getTokenFromRequest()
        );
    }

    public function createToken(
        string $name,
        ?string $userIp = null,
        ?string $fingerprint = null,
        ?string $userAgent = null,
        array $abilities = ['*'],
        ?\DateTimeInterface $expiresAt = null
    ): NewAccessToken {
        $plainTextToken = $this->generateTokenString();

        $token = $this->tokens()
            ->create(
                [
                    'name'        => $name,
                    'token'       => hash('sha256', $plainTextToken),
                    'abilities'   => $abilities,
                    'expires_at'  => $expiresAt,
                    'ip'          => $userIp,
                    'fingerprint' => $fingerprint,
                    'user_agent'  => $userAgent,
                ]
            );

        return new NewAccessToken(
            $token,
            $token->getKey() . '|' . $plainTextToken
        );
    }
}
