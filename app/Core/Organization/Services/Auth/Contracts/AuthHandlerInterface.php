<?php

declare(strict_types=1);

namespace App\Core\Organization\Services\Auth\Contracts;

use App\Core\Organization\Services\Auth\ValueObjects\Identifier;

interface AuthHandlerInterface extends Authenticatable, Logoutable, PasswordChangeable
{
    public function supports(?Identifier $identifier, ?string $authType): bool;
}
