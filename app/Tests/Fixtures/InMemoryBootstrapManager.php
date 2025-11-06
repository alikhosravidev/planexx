<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Contracts\BootstrapFileManagerInterface;

class InMemoryBootstrapManager implements BootstrapFileManagerInterface
{
    /** @var array<string,bool>|null */
    private ?array $map;

    public function __construct(?array $initial = null)
    {
        $this->map = $initial;
    }

    public function exists(): bool
    {
        return is_array($this->map);
    }

    public function read(): array
    {
        if (! is_array($this->map)) {
            return [];
        }
        $normalized = array_map(static fn ($v) => (bool)$v, $this->map);
        ksort($normalized);

        return $normalized;
    }

    public function write(array $map): bool
    {
        $filtered = array_map(static fn ($v) => (bool)$v, $map);
        ksort($filtered);
        $this->map = $filtered;

        return true;
    }
}
