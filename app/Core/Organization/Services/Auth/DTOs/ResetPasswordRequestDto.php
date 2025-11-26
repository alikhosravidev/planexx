<?php

declare(strict_types=1);

namespace App\Core\Organization\Services\Auth\DTOs;

use App\Core\Organization\Services\Auth\ValueObjects\Identifier;

final readonly class ResetPasswordRequestDto
{
    public function __construct(
        public Identifier        $identifier,
        public string            $code,
        public string            $password,
        public string            $repeatPassword,
        public ClientMetadataDto $clientMetadata,
    ) {
    }
}
