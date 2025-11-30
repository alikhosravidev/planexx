<?php

declare(strict_types=1);

namespace App\Services\QuickAccess;

use App\Contracts\Registry\RegistryBuilderInterface;
use App\Services\Registry\BaseRegistryManager;
use Illuminate\Support\Collection;

class QuickAccessManager extends BaseRegistryManager
{
    protected function getConfigKey(): string
    {
        return 'services.registry';
    }

    protected function getDefaultCachePrefix(): string
    {
        return 'quick_access_';
    }

    protected function createBuilder(): RegistryBuilderInterface
    {
        return new QuickAccessBuilder();
    }

    public function getTransformed(string $registryName): Collection
    {
        $items = $this->get($registryName);

        return $items->map(fn ($item) => $item->toArray());
    }
}
