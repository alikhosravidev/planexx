<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services;

use App\Services\ModuleManager;
use App\Tests\Fixtures\InMemoryBootstrapManager;
use App\Tests\Fixtures\InMemoryModuleDiscovery;
use Tests\UnitTestBase;

final class ModuleManagerTest extends UnitTestBase
{
    public function test_get_enabled_modules_with_empty_bootstrap(): void
    {
        // Arrange
        $discovery = new InMemoryModuleDiscovery(['A', 'B']);
        $bootstrap = new InMemoryBootstrapManager([]);
        $sut       = new ModuleManager($discovery, $bootstrap);

        // Act
        $enabled = $sut->getEnabledModules();

        // Assert
        $this->assertSame([], $enabled);
    }

    public function test_get_enabled_modules_with_existing_modules(): void
    {
        // Arrange
        $discovery = new InMemoryModuleDiscovery(['A', 'B', 'C']);
        $bootstrap = new InMemoryBootstrapManager(['A' => true, 'B' => false, 'C' => true]);
        $sut       = new ModuleManager($discovery, $bootstrap);

        // Act
        $enabled = $sut->getEnabledModules();

        // Assert
        sort($enabled);
        $this->assertSame(['A', 'C'], $enabled);
    }

    public function test_update_module_enables_disabled_module(): void
    {
        // Arrange
        $module    = 'Payment';
        $discovery = new InMemoryModuleDiscovery([$module]);
        $bootstrap = new InMemoryBootstrapManager([$module => false]);
        $sut       = new ModuleManager($discovery, $bootstrap);

        // Act
        $result = $sut->updateModule($module, true);

        // Assert
        $this->assertTrue($result);
        $this->assertSame([$module => true], $bootstrap->read());
    }

    public function test_update_module_disables_enabled_module(): void
    {
        // Arrange
        $module    = 'Catalog';
        $discovery = new InMemoryModuleDiscovery([$module]);
        $bootstrap = new InMemoryBootstrapManager([$module => true]);
        $sut       = new ModuleManager($discovery, $bootstrap);

        // Act
        $result = $sut->updateModule($module, false);

        // Assert
        $this->assertTrue($result);
        $this->assertSame([$module => false], $bootstrap->read());
    }

    public function test_ensure_bootstrap_file_creates_when_missing(): void
    {
        // Arrange
        $discovery = new InMemoryModuleDiscovery(['X', 'Y']);
        $bootstrap = new InMemoryBootstrapManager(null); // does not exist
        $sut       = new ModuleManager($discovery, $bootstrap);

        // Act
        $sut->ensureBootstrapFile();

        // Assert: newly created with defaults (enabled)
        $this->assertSame(['X' => true, 'Y' => true], $bootstrap->read());
    }

    public function test_regenerate_bootstrap_file_preserves_existing_states(): void
    {
        // Arrange
        $discovery = new InMemoryModuleDiscovery(['A', 'B', 'C']);
        $bootstrap = new InMemoryBootstrapManager(['A' => false, 'B' => true]);
        $sut       = new ModuleManager($discovery, $bootstrap);

        // Act
        $sut->regenerateBootstrapFile();

        // Assert: A stays disabled, B stays enabled, new C enabled by default
        $this->assertSame([
            'A' => false,
            'B' => true,
            'C' => true,
        ], $bootstrap->read());
    }

    public function test_discover_feature_modules_returns_valid_modules_only(): void
    {
        // Arrange
        $discovery = new InMemoryModuleDiscovery(['Ok1', 'Ok2']);
        $bootstrap = new InMemoryBootstrapManager([]);
        $sut       = new ModuleManager($discovery, $bootstrap);

        // Act
        $modules = $sut->discoverFeatureModules();

        // Assert
        $this->assertSame(['Ok1', 'Ok2'], $modules);
    }
}
