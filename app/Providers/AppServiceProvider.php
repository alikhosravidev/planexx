<?php

declare(strict_types=1);

namespace App\Providers;

use App\Commands\PrepareParallelTests;
use App\Contracts\BootstrapFileManagerInterface;
use App\Contracts\ModuleDiscoveryInterface;
use App\Core\BPMS\Providers\BPMSServiceProvider;
use App\Core\FormEngine\Providers\FormEngineServiceProvider;
use App\Core\Notify\Providers\NotifyServiceProvider;
use App\Core\Organization\Providers\OrganizationServiceProvider;
use App\Services\FilesystemModuleDiscovery;
use App\Services\Menu\MenuBuilder;
use App\Services\Menu\MenuManager;
use App\Services\ModuleManager;
use App\Services\PhpFileBootstrapManager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(QueryServiceProvider::class);
        $this->moduleDiscovery();
        $this->registerMenu();

        // Bind AI Image Service Factory
        //$this->app->singleton('ai_image_service_factory', function ($app) {
        //    return new AIImageServiceFactory(
        //        $app->make(AIImageService::class),
        //        $app->make(LoggerInterface::class),
        //        $app->make(HttpClient::class)
        //    );
        //});

        $this->registerCoreProviders();
        $this->registerModuleProviders();
    }

    public function boot(): void
    {
        $this->commands(
            PrepareParallelTests::class,
        );
        $this->registerCoreMenu();
    }

    protected function registerCoreMenu(): void
    {
        app('menu')->register('dashboard.sidebar', function (MenuBuilder $menu) {
            $menu->item('داشبورد', 'dashboard')
                ->route('dashboard')
                ->icon('fa-solid fa-chart-line')
                ->order(1);
        });
    }

    private function registerCoreProviders(): void
    {
        $this->app->register(OrganizationServiceProvider::class);
        $this->app->register(FormEngineServiceProvider::class);
        $this->app->register(BPMSServiceProvider::class);
        $this->app->register(NotifyServiceProvider::class);
    }

    private function registerModuleProviders(): void
    {
        /** @var ModuleManager $manager */
        $manager = $this->app->make(ModuleManager::class);

        $manager->ensureBootstrapFile();

        foreach ($manager->getEnabledModules() as $module) {
            $provider = "Modules\\{$module}\\Providers\\{$module}ServiceProvider";

            if (class_exists($provider)) {
                $this->app->register($provider);
            }
        }
    }

    private function moduleDiscovery(): void
    {
        $this->app->singleton(ModuleDiscoveryInterface::class, function ($app) {
            /** @var Filesystem $fs */
            $modulesPath = base_path('Modules');

            return new FilesystemModuleDiscovery(
                $app->make(Filesystem::class),
                $modulesPath
            );
        });

        $this->app->singleton(BootstrapFileManagerInterface::class, function ($app) {
            /** @var Filesystem $fs */
            $bootstrapFile = base_path('bootstrap/modules.php');

            return new PhpFileBootstrapManager(
                $app->make(Filesystem::class),
                $bootstrapFile
            );
        });

        // Bind ModuleManager with DI
        $this->app->singleton(ModuleManager::class, function ($app) {
            return new ModuleManager(
                $app->make(ModuleDiscoveryInterface::class),
                $app->make(BootstrapFileManagerInterface::class),
            );
        });
    }

    private function registerMenu(): void
    {
        $this->app->singleton(MenuManager::class, function () {
            return new MenuManager();
        });
        $this->app->alias(MenuManager::class, 'menu');
    }
}
