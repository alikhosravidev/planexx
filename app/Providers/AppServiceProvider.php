<?php

declare(strict_types=1);

namespace App\Providers;

use App\Bus\Contracts\UserProvider;
use App\Commands\PrepareParallelTests;
use App\Contracts\BootstrapFileManagerInterface;
use App\Contracts\ModuleDiscoveryInterface;
use App\Core\FormWizard\Providers\FormWizardServiceProvider;
use App\Core\Organization\Providers\OrganizationServiceProvider;
use App\Core\User\Providers\UserServiceProvider;
use App\Core\User\Repositories\UserRepository;
use App\Services\AIImageService\AIImageService;
use App\Services\AIImageService\AIImageServiceFactory;
use App\Services\FilesystemModuleDiscovery;
use App\Services\HttpClient;
use App\Services\ModuleManager;
use App\Services\PhpFileBootstrapManager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
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

        // Bind AI Image Service Factory
        $this->app->singleton('ai_image_service_factory', function ($app) {
            return new AIImageServiceFactory(
                $app->make(AIImageService::class),
                $app->make(LoggerInterface::class),
                $app->make(HttpClient::class)
            );
        });

        // Bind ModuleManager with DI
        $this->app->singleton(ModuleManager::class, function ($app) {
            return new ModuleManager(
                $app->make(ModuleDiscoveryInterface::class),
                $app->make(BootstrapFileManagerInterface::class),
            );
        });

        $this->registerCoreProviders();
        $this->registerModuleProviders();
    }

    public function boot(): void
    {
        $this->commands(
            PrepareParallelTests::class,
        );
        $this->app->bind(UserProvider::class, function ($app) {
            return resolve(UserRepository::class);
        });
    }

    private function registerCoreProviders(): void
    {
        $this->app->register(UserServiceProvider::class);
        $this->app->register(OrganizationServiceProvider::class);
        $this->app->register(FormWizardServiceProvider::class);
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
}
