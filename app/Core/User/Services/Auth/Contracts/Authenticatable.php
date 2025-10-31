<?php

declare(strict_types=1);

namespace App\Core\User\Services\Auth\Contracts;

use App\Core\User\Services\Auth\DTOs\AuthRequestDto;
use App\Core\User\Services\Auth\DTOs\AuthResponse;
use App\Core\User\Services\Auth\ValueObjects\Identifier;

interface Authenticatable
{
    public function init(Identifier $identifier): AuthResponse;

    public function auth(AuthRequestDto $requestData): AuthResponse;
}
