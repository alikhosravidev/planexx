<?php

declare(strict_types=1);

namespace App\Core\FormWizard\Providers;

use App\Utilities\ProviderUtility;
use Illuminate\Support\ServiceProvider;

class FormWizardServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
    }

    public function boot(): void
    {
        //$this->loadRoutesFrom(
        //    ProviderUtility::corePath('FormWizard/Routes/V1/admin.php')
        //);

        $this->loadMigrationsFrom(
            ProviderUtility::corePath('FormWizard/Database/Migrations')
        );
    }
}
