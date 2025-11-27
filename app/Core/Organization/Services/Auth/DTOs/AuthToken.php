<?php

declare(strict_types=1);

namespace App\Core\Organization\Services\Auth\DTOs;

final class AuthToken
{
    public function __construct(
        public readonly string  $value,
        public readonly string  $type,
        public readonly ?string $refreshToken = null,
    ) {
    }
}
