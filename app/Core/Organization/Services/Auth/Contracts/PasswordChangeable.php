<?php

declare(strict_types=1);

namespace App\Core\Organization\Services\Auth\Contracts;

use App\Core\Organization\Services\Auth\DTOs\AuthResponse;
use App\Core\Organization\Services\Auth\DTOs\ChangePasswordRequestDto;

interface PasswordChangeable
{
    public function changePassword(ChangePasswordRequestDto $requestData): AuthResponse;
}
