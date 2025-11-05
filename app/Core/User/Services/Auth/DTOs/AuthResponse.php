<?php

declare(strict_types=1);

namespace App\Core\User\Services\Auth\DTOs;

use App\Core\User\Entities\User;
use App\Core\User\Services\Auth\ValueObjects\Identifier;
use App\Core\User\Services\OTPService\OTPResponse;

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
