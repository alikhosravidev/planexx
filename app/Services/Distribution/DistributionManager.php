<?php

declare(strict_types=1);

namespace App\Services\Distribution;

use App\Contracts\Registry\RegistryBuilderInterface;
use App\Services\Registry\BaseRegistryManager;
use Illuminate\Support\Collection;

class DistributionManager extends BaseRegistryManager
{
    protected function getConfigKey(): string
    {
        return 'services.registry';
    }

    protected function getDefaultCachePrefix(): string
    {
        return 'distribution_';
    }

    protected function createBuilder(): RegistryBuilderInterface
    {
        return new DistributionBuilder();
    }

    public function getTransformed(string $registryName): Collection
    {
        $items = $this->get($registryName);

        return $items->map(fn ($item) => $item->toArray());
    }
}
