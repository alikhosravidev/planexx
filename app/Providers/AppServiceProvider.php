<?php

declare(strict_types=1);

namespace App\Providers;

use function app;

use App\Commands\FetchEntitiesCommand;
use App\Commands\FetchEnumsCommand;
use App\Commands\FetchEventsCommand;
use App\Commands\PrepareParallelTests;
use App\Contracts\BootstrapFileManagerInterface;
use App\Contracts\ModuleDiscoveryInterface;
use App\Core\BPMS\Providers\BPMSServiceProvider;
use App\Core\FileManager\Providers\FileManagerServiceProvider;
use App\Core\FormEngine\Providers\FormEngineServiceProvider;
use App\Core\Notify\Providers\NotifyServiceProvider;
use App\Core\Organization\Providers\OrganizationServiceProvider;
use App\Macros\BlueprintMixin;
use App\Registrars\DashboardMenuRegistrar;
use App\Registrars\DashboardQuickAccessRegistrar;
use App\Registrars\DashboardStatsRegistrar;
use App\Services\Distribution\DistributionManager;
use App\Services\FilesystemModuleDiscovery;
use App\Services\Menu\MenuManager;
use App\Services\MetadataMappers\MapEntityMetadata;
use App\Services\ModuleManager;
use App\Services\PhpFileBootstrapManager;
use App\Services\QuickAccess\QuickAccessManager;
use App\Services\ResourceRegistrar;
use App\Services\Stats\StatManager;
use App\Utilities\CustomRequestValidator;
use Applications\AdminPanel\AdminPanelServiceProvider;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\ResourceRegistrar as BaseResourceRegistrar;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BaseResourceRegistrar::class, ResourceRegistrar::class);
        $this->app->register(QueryServiceProvider::class);

        if (! $this->app->isProduction()) {
            $this->app->register(TelescopeServiceProvider::class);
            $this->app->register(IdeHelperServiceProvider::class);
        }

        $this->moduleDiscovery();
        $this->registerMenu();
        $this->registerStats();
        $this->registerQuickAccess();
        $this->registerDistribution();

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
        $this->registerApplicationsProviders();
    }

    public function boot(): void
    {
        MapEntityMetadata::enforceMorphMap();
        $this->commands(
            PrepareParallelTests::class,
            FetchEntitiesCommand::class,
            FetchEnumsCommand::class,
            FetchEventsCommand::class
        );
        $this->registerCustomValidations();
        $this->loadMacro();
        app('menu')->registerBy(DashboardMenuRegistrar::class);
        app('stat')->registerBy(DashboardStatsRegistrar::class);
        app('quick-access')->registerBy(DashboardQuickAccessRegistrar::class);
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

    private function registerCoreProviders(): void
    {
        $this->app->register(OrganizationServiceProvider::class);
        $this->app->register(FileManagerServiceProvider::class);
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

    private function registerApplicationsProviders(): void
    {
        $this->app->register(AdminPanelServiceProvider::class);
    }

    private function registerMenu(): void
    {
        $this->app->singleton(MenuManager::class, function () {
            return new MenuManager();
        });
        $this->app->alias(MenuManager::class, 'menu');
    }

    private function registerStats(): void
    {
        $this->app->singleton(StatManager::class, function () {
            return new StatManager();
        });
        $this->app->alias(StatManager::class, 'stat');
    }

    private function registerQuickAccess(): void
    {
        $this->app->singleton(QuickAccessManager::class, function () {
            return new QuickAccessManager();
        });
        $this->app->alias(QuickAccessManager::class, 'quick-access');
    }

    private function registerDistribution(): void
    {
        $this->app->singleton(DistributionManager::class, function () {
            return new DistributionManager();
        });
        $this->app->alias(DistributionManager::class, 'distribution');
    }

    private function registerCustomValidations(): void
    {
        CustomRequestValidator::registerMobileValidation();
        CustomRequestValidator::registerFullNameValidation();
        CustomRequestValidator::registerTableNameValidator();
        CustomRequestValidator::registerEntityValidator();
    }

    private function loadMacro(): void
    {
        Blueprint::mixin(new BlueprintMixin());
    }
}
