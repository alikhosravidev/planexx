<?php

declare(strict_types=1);

namespace App\Core\Organization\Services\Auth\Contracts;

interface Logoutable
{
    public function logout(?string $token = null): bool;
}
