<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\BootstrapFileManagerInterface;
use App\Contracts\ModuleDiscoveryInterface;

readonly class ModuleManager
{
    public function __construct(
        protected ModuleDiscoveryInterface      $discovery,
        protected BootstrapFileManagerInterface $bootstrapManager,
    ) {
    }

    public function getEnabledModules(): array
    {
        $map = $this->bootstrapManager->read();

        return array_values(array_keys(array_filter($map, fn ($v) => (bool) $v)));
    }

    public function discoverFeatureModules(): array
    {
        return $this->discovery->discover();
    }

    public function updateModule(string $module, bool $status): bool
    {
        $map          = $this->bootstrapManager->read();
        $map[$module] = $status;

        return $this->bootstrapManager->write($map);
    }

    public function regenerateBootstrapFile(): void
    {
        $existing = $this->bootstrapManager->read();
        $features = $this->discovery->discover();

        $new = [];

        foreach ($features as $module) {
            $new[$module] = ! array_key_exists($module, $existing) || (bool) ($existing[$module] ?? true);
        }
        $this->bootstrapManager->write($new);
    }

    public function ensureBootstrapFile(): void
    {
        if (! $this->bootstrapManager->exists()) {
            $this->regenerateBootstrapFile();

            return;
        }
        $data = $this->bootstrapManager->read();

        if (empty($data)) {
            $this->regenerateBootstrapFile();
        }
    }
}
