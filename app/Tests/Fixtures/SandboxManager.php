<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use Illuminate\Filesystem\Filesystem;

class SandboxManager
{
    public function __construct(private readonly Filesystem $fs)
    {
    }

    public function makeSandboxRoot(string $prefix = 'module_manager_'): string
    {
        $root = storage_path('framework/testing/' . $prefix . bin2hex(random_bytes(6)));
        $this->fs->deleteDirectory($root);
        $this->fs->ensureDirectoryExists($root);

        return $root;
    }

    public function modulesPath(string $root): string
    {
        return $root . DIRECTORY_SEPARATOR . 'Modules';
    }

    public function bootstrapFile(string $root): string
    {
        return $root . DIRECTORY_SEPARATOR . 'bootstrap' . DIRECTORY_SEPARATOR . 'modules.php';
    }

    public function createModule(string $modulesPath, string $name, bool $withProviders = true): void
    {
        $moduleDir = $modulesPath . DIRECTORY_SEPARATOR . $name;
        $this->fs->ensureDirectoryExists($moduleDir);

        if ($withProviders) {
            $this->fs->ensureDirectoryExists($moduleDir . DIRECTORY_SEPARATOR . 'Providers');
        }
    }

    public function cleanup(string $root): void
    {
        $this->fs->deleteDirectory($root);
    }
}
