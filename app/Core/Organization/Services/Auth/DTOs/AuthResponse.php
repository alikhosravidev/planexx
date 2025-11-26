<?php

declare(strict_types=1);

namespace App\Core\Organization\Services\Auth\DTOs;

use App\Core\Organization\Entities\User;
use App\Core\Organization\Services\Auth\ValueObjects\Identifier;
use App\Core\Organization\Services\OTPService\OTPResponse;

final readonly class AuthResponse
{
    public function __construct(
        public string       $message,
        public ?Identifier  $identifier = null,
        public ?AuthToken   $token = null,
        public ?User        $user = null,
        public ?string      $nextStep = null,
        public ?OTPResponse $otpData = null,
        public bool         $isRegistered = false,
    ) {
    }
}
