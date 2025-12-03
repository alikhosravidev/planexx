<?php

declare(strict_types=1);

namespace App\Core\FileManager\Providers;

use App\Core\FileManager\Contracts\FolderRepositoryInterface;
use App\Core\FileManager\Registrars\FileManagerMenuRegistrar;
use App\Core\FileManager\Repositories\FolderRepository;
use App\Utilities\ProviderUtility;
use Illuminate\Support\ServiceProvider;

class FileManagerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);

        $this->app->bind(
            FolderRepositoryInterface::class,
            FolderRepository::class
        );

        $this->app->bind(
            \App\Core\FileManager\Contracts\FileRepositoryInterface::class,
            \App\Core\FileManager\Repositories\FileRepository::class
        );

        $this->app->bind(
            \App\Core\FileManager\Contracts\FavoriteRepositoryInterface::class,
            \App\Core\FileManager\Repositories\FavoriteRepository::class
        );
    }

    public function boot(): void
    {
        if ($path = ProviderUtility::corePath('FileManager/Routes/API/v1/admin.php')) {
            $this->loadRoutesFrom($path);
        }

        if ($path = ProviderUtility::corePath('FileManager/Routes/web.php')) {
            $this->loadRoutesFrom($path);
        }

        if ($path = ProviderUtility::corePath('FileManager/Database/Migrations')) {
            $this->loadMigrationsFrom($path);
        }

        if ($path = ProviderUtility::corePath('FileManager/Resources/lang')) {
            $this->loadTranslationsFrom($path, 'FileManager');
        }

        if ($path = ProviderUtility::corePath('FileManager/Resources/views')) {
            $this->loadViewsFrom($path, 'FileManager');
        }

        app('menu')->registerBy(FileManagerMenuRegistrar::class);
    }
}
