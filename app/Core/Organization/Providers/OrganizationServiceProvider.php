<?php

declare(strict_types=1);

namespace App\Core\Organization\Providers;

use App\Utilities\ProviderUtility;
use Illuminate\Support\ServiceProvider;

class OrganizationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(
            ProviderUtility::corePath('Organization/Database/Migrations')
        );
    }
}
