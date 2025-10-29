<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ModuleManager;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind ModuleManager
        $this->app->singleton(ModuleManager::class, fn () => new ModuleManager());

        // Register Core and Feature module service providers
        $this->registerModuleProviders();
    }

    public function boot(): void
    {
        // Nothing required here for now
    }

    private function registerModuleProviders(): void
    {
        /** @var ModuleManager $manager */
        $manager = $this->app->make(ModuleManager::class);

        // Register enabled Feature module providers: Modules\\{Module}\\Providers\\{Module}ServiceProvider
        foreach ($manager->getEnabledModules() as $module) {
            $provider = "Modules\\\\{$module}\\\\Providers\\\\{$module}ServiceProvider";
            if (class_exists($provider)) {
                $this->app->register($provider);
            }
        }
    }
}
