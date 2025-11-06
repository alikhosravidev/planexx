<?php

declare(strict_types=1);

namespace App\Contracts;

interface ModuleDiscoveryInterface
{
    /**
     * Discover available feature modules.
     * Should return an array of module names (directory basenames).
     *
     * @return array<int, string>
     */
    public function discover(): array;
}
