<?php

declare(strict_types=1);

namespace App\Contracts\Registry;

use Illuminate\Support\Collection;

interface RegistryManagerInterface
{
    public function register(string $registryName, callable $callback): static;

    public function get(string $registryName): Collection;

    public function toArray(string $registryName): array;

    public function clearCache(?string $registryName = null): void;

    public function has(string $registryName): bool;
}
