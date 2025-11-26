<?php

declare(strict_types=1);

namespace App\Core\Organization\Services\Auth\Contracts;

use App\Core\Organization\Services\Auth\DTOs\AuthResponse;
use App\Core\Organization\Services\Auth\DTOs\ResetPasswordRequestDto;
use App\Core\Organization\Services\Auth\ValueObjects\Identifier;

interface PasswordResetable
{
    public function initResetPassword(Identifier $identifier): AuthResponse;

    public function resetPassword(ResetPasswordRequestDto $requestData): AuthResponse;
}
