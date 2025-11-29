<?php

declare(strict_types=1);

namespace App\Services\QuickAccess;

use App\Contracts\Registry\RegistryBuilderInterface;
use App\Services\Registry\BaseRegistryManager;

class QuickAccessManager extends BaseRegistryManager
{
    protected function getConfigKey(): string
    {
        return 'services.quick_access';
    }

    protected function getDefaultCachePrefix(): string
    {
        return 'quick_access_';
    }

    protected function createBuilder(): RegistryBuilderInterface
    {
        return new QuickAccessBuilder();
    }

}
