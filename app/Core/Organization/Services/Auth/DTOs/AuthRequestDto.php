<?php

declare(strict_types=1);

namespace App\Core\Organization\Services\Auth\DTOs;

use App\Core\Organization\Services\Auth\ValueObjects\Identifier;
use App\Utilities\StringUtility;

final class AuthRequestDto
{
    public readonly string $password;

    public function __construct(
        public readonly Identifier $identifier,
        string $password,
        public readonly ClientMetadataDto $clientMetadata,
        public readonly ?string $authType = null,
    ) {
        $this->password = StringUtility::numberToEn($password);
    }
}
