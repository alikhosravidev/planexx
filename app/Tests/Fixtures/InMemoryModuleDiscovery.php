<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Contracts\ModuleDiscoveryInterface;

class InMemoryModuleDiscovery implements ModuleDiscoveryInterface
{
    /** @var array<int,string> */
    private array $modules;

    /**
     * @param array<int,string> $modules
     */
    public function __construct(array $modules = [])
    {
        $this->modules = array_values($modules);
    }

    public function setModules(array $modules): void
    {
        $this->modules = array_values($modules);
    }

    public function discover(): array
    {
        $out = array_values($this->modules);
        sort($out);

        return $out;
    }
}
