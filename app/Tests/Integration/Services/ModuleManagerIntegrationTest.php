<?php

declare(strict_types=1);

namespace App\Tests\Integration\Services;

use App\Services\FilesystemModuleDiscovery;
use App\Services\ModuleManager;
use App\Services\PhpFileBootstrapManager;
use App\Tests\Fixtures\SandboxManager;
use Illuminate\Filesystem\Filesystem;
use Tests\IntegrationTestBase;

final class ModuleManagerIntegrationTest extends IntegrationTestBase
{
    private Filesystem $fs;
    private SandboxManager $sandbox;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fs      = $this->app->make(Filesystem::class);
        $this->sandbox = new SandboxManager($this->fs);
    }

    public function test_full_workflow_discover_and_manage_modules(): void
    {
        // Arrange
        $root          = $this->sandbox->makeSandboxRoot();
        $modulesPath   = $this->sandbox->modulesPath($root);
        $bootstrapFile = $this->sandbox->bootstrapFile($root);

        $this->fs->ensureDirectoryExists($modulesPath);
        $this->fs->ensureDirectoryExists(dirname($bootstrapFile));

        // Create modules, one valid (with Providers), one invalid
        $this->sandbox->createModule($modulesPath, 'Blog', true);
        $this->sandbox->createModule($modulesPath, 'Playground', false); // should be ignored
        $this->sandbox->createModule($modulesPath, 'Shop', true);

        $discovery = new FilesystemModuleDiscovery($this->fs, $modulesPath);
        $bootstrap = new PhpFileBootstrapManager($this->fs, $bootstrapFile);
        $sut       = new ModuleManager($discovery, $bootstrap);

        // Act: ensure bootstrap is created
        $sut->ensureBootstrapFile();

        // Assert: discovered only valid modules and default enabled
        $this->assertSame([
            'Blog' => true,
            'Shop' => true,
        ], $bootstrap->read());

        // Act: disable Blog, keep Shop enabled
        $this->assertTrue($sut->updateModule('Blog', false));

        // Assert: getEnabledModules reflects state
        $enabled = $sut->getEnabledModules();
        sort($enabled);
        $this->assertSame(['Shop'], $enabled);

        // Act: add new valid module Docs and regenerate
        $this->sandbox->createModule($modulesPath, 'Docs', true);
        $sut->regenerateBootstrapFile();

        // Assert: preserves Blog=false, Shop=true, new Docs=true; missing modules removed
        $this->assertSame([
            'Blog' => false,
            'Docs' => true,
            'Shop' => true,
        ], $bootstrap->read());

        // Cleanup
        $this->sandbox->cleanup($root);
    }

    public function test_bootstrap_file_persistence_across_operations(): void
    {
        // Arrange
        $root          = $this->sandbox->makeSandboxRoot();
        $modulesPath   = $this->sandbox->modulesPath($root);
        $bootstrapFile = $this->sandbox->bootstrapFile($root);
        $this->fs->ensureDirectoryExists($modulesPath);
        $this->sandbox->createModule($modulesPath, 'Catalog', true);

        $discovery = new FilesystemModuleDiscovery($this->fs, $modulesPath);
        $bootstrap = new PhpFileBootstrapManager($this->fs, $bootstrapFile);
        $sut       = new ModuleManager($discovery, $bootstrap);

        // Act
        $sut->ensureBootstrapFile();
        $this->assertTrue($sut->updateModule('Catalog', false));

        // Recreate manager to ensure reading from disk
        $sut2 = new ModuleManager(
            new FilesystemModuleDiscovery($this->fs, $modulesPath),
            new PhpFileBootstrapManager($this->fs, $bootstrapFile)
        );

        // Assert
        $this->assertSame(['Catalog' => false], $bootstrap->read());
        $this->assertSame([], $sut2->getEnabledModules());

        // Cleanup
        $this->sandbox->cleanup($root);
    }

    public function test_module_state_consistency_after_regeneration(): void
    {
        // Arrange
        $root          = $this->sandbox->makeSandboxRoot();
        $modulesPath   = $this->sandbox->modulesPath($root);
        $bootstrapFile = $this->sandbox->bootstrapFile($root);
        $this->fs->ensureDirectoryExists($modulesPath);
        $this->sandbox->createModule($modulesPath, 'A', true);
        $this->sandbox->createModule($modulesPath, 'B', true);

        $discovery = new FilesystemModuleDiscovery($this->fs, $modulesPath);
        $bootstrap = new PhpFileBootstrapManager($this->fs, $bootstrapFile);
        $sut       = new ModuleManager($discovery, $bootstrap);

        // Act
        $sut->ensureBootstrapFile(); // A,B => true
        $this->assertTrue($sut->updateModule('A', false));

        // Add new C and remove B from filesystem
        $this->sandbox->createModule($modulesPath, 'C', true);
        $this->fs->deleteDirectory($modulesPath . DIRECTORY_SEPARATOR . 'B');
        $sut->regenerateBootstrapFile();

        // Assert: A=false preserved, B removed, C added true
        $this->assertSame([
            'A' => false,
            'C' => true,
        ], $bootstrap->read());

        // Cleanup
        $this->sandbox->cleanup($root);
    }
}
