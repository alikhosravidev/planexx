<?php

declare(strict_types=1);

namespace App\Core\User\Services\Auth\Contracts;

use App\Core\User\Services\Auth\DTOs\AuthResponse;
use App\Core\User\Services\Auth\DTOs\ChangePasswordRequestDto;

interface PasswordChangeable
{
    public function changePassword(ChangePasswordRequestDto $requestData): AuthResponse;
}
