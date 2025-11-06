<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\BootstrapFileManagerInterface;
use Illuminate\Filesystem\Filesystem;
use Throwable;

readonly class PhpFileBootstrapManager implements BootstrapFileManagerInterface
{
    public function __construct(
        private Filesystem $files,
        private string     $bootstrapFile
    ) {
    }

    public function exists(): bool
    {
        return $this->files->exists($this->bootstrapFile);
    }

    public function read(): array
    {
        try {
            if (! $this->files->exists($this->bootstrapFile)) {
                return [];
            }
            $data = include $this->bootstrapFile;

            if (is_array($data)) {
                // normalize to bool
                $normalized = array_map(static fn ($v) => (bool) $v, $data);
                ksort($normalized);

                return $normalized;
            }
        } catch (Throwable) {
            // fallthrough to empty
        }

        return [];
    }

    public function write(array $map): bool
    {
        $filtered = array_map(static fn ($v) => (bool) $v, $map);
        ksort($filtered);
        $content = '<?php return ' . var_export($filtered, true) . ";\n";

        try {
            $this->files->ensureDirectoryExists(dirname($this->bootstrapFile));
            $this->files->put($this->bootstrapFile, $content);

            return true;
        } catch (Throwable) {
            return false;
        }
    }
}
