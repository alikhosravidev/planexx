<?php

declare(strict_types=1);

namespace App\Providers;

use App\Core\User\Providers\UserServiceProvider;
use App\Services\ModuleManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind ModuleManager
        $this->app->singleton(ModuleManager::class, fn () => new ModuleManager());

        $this->registerCoreProviders();

        $this->registerModuleProviders();
    }

    public function boot(): void
    {
        // Nothing required here for now
    }

    private function registerCoreProviders(): void
    {
        $this->app->register(UserServiceProvider::class);
    }

    private function registerModuleProviders(): void
    {
        /** @var ModuleManager $manager */
        $manager = $this->app->make(ModuleManager::class);

        // Register enabled Feature module providers: Modules\\{Module}\\Providers\\{Module}ServiceProvider
        foreach ($manager->getEnabledModules() as $module) {
            $provider = "Modules\\{$module}\\Providers\\{$module}ServiceProvider";

            if (class_exists($provider)) {
                $this->app->register($provider);
            }
        }
    }
}
