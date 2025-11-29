<?php

declare(strict_types=1);

namespace App\Contracts;

interface MenuRegistrar
{
    public function register(string $menuName, callable $callback): static;
}
