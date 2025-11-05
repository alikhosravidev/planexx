<?php

declare(strict_types=1);

namespace App\Core\User\Services\Auth\DTOs;

use App\Core\User\Entities\User;
use App\Core\User\Services\Auth\ValueObjects\Identifier;
use App\Core\User\Services\OTPService\OTPResponse;

final class AuthResponse
{
    public function __construct(
        public readonly string $message,
        public readonly ?Identifier $identifier = null,
        public readonly ?AuthToken $token = null,
        public readonly ?User $user = null,
        public readonly ?string $nextStep = null,
        public readonly ?OTPResponse $otpData = null,
        public readonly bool $isRegistered = false,
    ) {
    }
}
