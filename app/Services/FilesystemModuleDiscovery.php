<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ModuleDiscoveryInterface;
use Illuminate\Filesystem\Filesystem;

readonly class FilesystemModuleDiscovery implements ModuleDiscoveryInterface
{
    public function __construct(
        private Filesystem $files,
        private string     $modulesPath
    ) {
    }

    public function discover(): array
    {
        if (! $this->files->isDirectory($this->modulesPath)) {
            return [];
        }

        $dirs = array_filter(
            $this->files->directories($this->modulesPath),
            function (string $dir): bool {
                return $this->files->isDirectory($dir . DIRECTORY_SEPARATOR . 'Providers');
            }
        );

        $names = array_map(static fn (string $path) => basename($path), $dirs);
        sort($names);

        return array_values($names);
    }
}
