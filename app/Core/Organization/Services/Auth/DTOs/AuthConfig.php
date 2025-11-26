<?php

declare(strict_types=1);

namespace App\Core\Organization\Services\Auth\DTOs;

use App\Core\Organization\Services\OTPService\OTPConfig;

final class AuthConfig
{
    public function __construct(
        public readonly OTPConfig $otpConfig,
        public readonly PasswordConfig $passwordConfig,
        public readonly string $authGuard,
        public readonly string $authTokenName,
        public readonly string $usernameValidationRegex,
        public readonly string $usernameValidationMessage,
    ) {
    }
}
