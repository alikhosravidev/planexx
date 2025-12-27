<?php

declare(strict_types=1);

namespace App\Core\FileManager\Providers;

use App\Core\FileManager\Registrars\FileManagerMenuRegistrar;
use App\Core\FileManager\Registrars\FileManagerQuickAccessRegistrar;
use App\Utilities\ProviderUtility;
use Illuminate\Support\ServiceProvider;

class FileManagerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
    }

    public function boot(): void
    {
        if ($path = ProviderUtility::corePath('FileManager/Routes/V1/admin.php')) {
            $this->loadRoutesFrom($path);
        }

        if ($path = ProviderUtility::corePath('FileManager/Routes/V1/client.php')) {
            $this->loadRoutesFrom($path);
        }

        if ($path = ProviderUtility::corePath('FileManager/Database/Migrations')) {
            $this->loadMigrationsFrom($path);
        }

        if ($path = ProviderUtility::corePath('FileManager/Resources/lang')) {
            $this->loadTranslationsFrom($path, 'FileManager');
        }

        app('menu')->registerBy(FileManagerMenuRegistrar::class);
        app('quick-access')->registerBy(FileManagerQuickAccessRegistrar::class);
    }
}
