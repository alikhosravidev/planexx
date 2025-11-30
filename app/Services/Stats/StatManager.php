<?php

declare(strict_types=1);

namespace App\Services\Stats;

use App\Contracts\Registry\RegistryBuilderInterface;
use App\Services\Registry\BaseRegistryManager;
use Illuminate\Support\Collection;

class StatManager extends BaseRegistryManager
{
    protected function getConfigKey(): string
    {
        return 'services.registry';
    }

    protected function getDefaultCachePrefix(): string
    {
        return 'stats_';
    }

    protected function createBuilder(): RegistryBuilderInterface
    {
        return new StatBuilder();
    }

    public function getTransformed(string $registryName): Collection
    {
        $items = $this->get($registryName);

        return $items->map(fn ($item) => $item->toArray());
    }
}
