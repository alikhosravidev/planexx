<?php

declare(strict_types=1);

namespace App\Core\Organization\Services\Auth\Contracts;

use App\Core\Organization\Services\Auth\DTOs\AuthRequestDto;
use App\Core\Organization\Services\Auth\DTOs\AuthResponse;
use App\Core\Organization\Services\Auth\ValueObjects\Identifier;

interface Authenticatable
{
    public function init(Identifier $identifier): AuthResponse;

    public function auth(AuthRequestDto $requestData): AuthResponse;
}
