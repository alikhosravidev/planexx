<?php

declare(strict_types=1);

namespace App\Core\User\Services\Auth\DTOs;

final class AuthToken
{
    public function __construct(
        public readonly string $token,
        public readonly string $type,
        public readonly ?string $refreshToken = null,
    ) {
    }
}
