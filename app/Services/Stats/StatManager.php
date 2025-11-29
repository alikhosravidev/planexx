<?php

declare(strict_types=1);

namespace App\Services\Stats;

use App\Contracts\Registry\RegistryBuilderInterface;
use App\Services\Registry\BaseRegistryManager;

class StatManager extends BaseRegistryManager
{
    protected function getConfigKey(): string
    {
        return 'services.stats';
    }

    protected function getDefaultCachePrefix(): string
    {
        return 'stats_';
    }

    protected function createBuilder(): RegistryBuilderInterface
    {
        return new StatBuilder();
    }

}
