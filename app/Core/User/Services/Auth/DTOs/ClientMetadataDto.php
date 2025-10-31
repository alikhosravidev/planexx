<?php

declare(strict_types=1);

namespace App\Core\User\Services\Auth\DTOs;

final class ClientMetadataDto
{
    public function __construct(
        public readonly string $ipAddress,
        public readonly ?string $userAgent = null,
        public readonly ?string $fingerprint = null,
    ) {
    }
}
