<?php

declare(strict_types=1);

namespace App\Core\Organization\Services\Auth\DTOs;

use App\Core\Organization\Services\Auth\ValueObjects\Identifier;

final readonly class ChangePasswordRequestDto
{
    public function __construct(
        public Identifier $identifier,
        public string     $password,
        public string     $newPassword,
    ) {
    }
}
