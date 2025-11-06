<?php

declare(strict_types=1);

namespace App\Contracts;

interface BootstrapFileManagerInterface
{
    /**
     * Whether the bootstrap mapping file exists and is readable.
     */
    public function exists(): bool;

    /**
     * Read the module enable/disable map from storage.
     * Must return an associative array: [moduleName => bool].
     * On any error, return an empty array.
     *
     * @return array<string, bool>
     */
    public function read(): array;

    /**
     * Persist the given module enable/disable map.
     * Should atomically write and return true on success.
     *
     * @param array<string, bool> $map
     */
    public function write(array $map): bool;
}
