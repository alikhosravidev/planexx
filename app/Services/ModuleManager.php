<?php

namespace App\Services;

use Illuminate\Filesystem\Filesystem;

class ModuleManager
{
    protected string $featurePath;
    protected string $bootstrapFile;
    protected Filesystem $files;

    public function __construct()
    {
        $this->files = new Filesystem();
        $this->featurePath = base_path('Modules');
        $this->bootstrapFile = base_path('bootstrap/modules.php');

        // Ensure we have a valid bootstrap file at startup
        $this->ensureBootstrapFile();
    }

    public function getEnabledModules(): array
    {
        $map = $this->readBootstrapFile();
        // File contains only feature modules; return those marked true
        return array_values(array_keys(array_filter($map, fn ($v) => (bool) $v)));
    }

    public function discoverFeatureModules(): array
    {
        if (!$this->files->isDirectory($this->featurePath)) {
            return [];
        }
        $dirs = array_filter($this->files->directories($this->featurePath), function ($dir) {
            return $this->files->isDirectory($dir . DIRECTORY_SEPARATOR . 'Providers');
        });
        return array_values(array_map(fn ($path) => basename($path), $dirs));
    }

    public function updateModule(string $module, bool $status): bool
    {
        $map = $this->readBootstrapFile();
        $map[$module] = $status;
        return $this->writeBootstrapFile($map);
    }

    public function regenerateBootstrapFile(): void
    {
        $existing = $this->readBootstrapFile();
        $features = $this->discoverFeatureModules();

        $new = [];
        foreach ($features as $module) {
            // Preserve previous state when available; default to true for newly discovered modules
            $new[$module] = ! array_key_exists($module, $existing) || (bool) $existing[$module];
        }
        $this->writeBootstrapFile($new);
    }

    private function ensureBootstrapFile(): void
    {
        if (!$this->files->exists($this->bootstrapFile)) {
            $this->regenerateBootstrapFile();
            return;
        }
        // Validate the file format
        $data = $this->readBootstrapFile();
        if (!is_array($data)) {
            $this->regenerateBootstrapFile();
        }
    }

    private function readBootstrapFile(): array
    {
        try {
            if (!$this->files->exists($this->bootstrapFile)) {
                return [];
            }
            $data = include $this->bootstrapFile;
            if (is_array($data)) {
                // Normalize to boolean map
                return array_map(fn ($v) => (bool) $v, $data);
            }
        } catch (\Throwable $e) {
            // Graceful degradation
        }
        return [];
    }

    private function writeBootstrapFile(array $map): bool
    {
        // Normalize and sort
        $filtered = array_map(fn ($v) => (bool) $v, $map);
        ksort($filtered);

        $content = "<?php return ".var_export($filtered, true).";\n";
        try {
            $this->files->put($this->bootstrapFile, $content);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
