<?php

declare(strict_types=1);

namespace App\Core\User\Services\Auth\DTOs;

final readonly class PasswordConfig
{
    public function __construct(
        public string $validationRegex
    ) {
    }
}
