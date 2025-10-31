<?php

declare(strict_types=1);

namespace App\Core\User\Services\Auth\Contracts;

use App\Core\User\Services\Auth\DTOs\AuthResponse;
use App\Core\User\Services\Auth\DTOs\ResetPasswordRequestDto;
use App\Core\User\Services\Auth\ValueObjects\Identifier;

interface PasswordResetable
{
    public function initResetPassword(Identifier $identifier): AuthResponse;

    public function resetPassword(ResetPasswordRequestDto $requestData): AuthResponse;
}
