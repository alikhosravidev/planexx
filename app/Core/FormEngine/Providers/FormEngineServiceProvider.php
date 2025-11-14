<?php

declare(strict_types=1);

namespace App\Core\FormEngine\Providers;

use App\Utilities\ProviderUtility;
use Illuminate\Support\ServiceProvider;

class FormEngineServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
    }

    public function boot(): void
    {
        //$this->loadRoutesFrom(
        //    ProviderUtility::corePath('FormEngine/Routes/V1/admin.php')
        //);

        $this->loadMigrationsFrom(
            ProviderUtility::corePath('FormEngine/Database/Migrations')
        );
    }
}
