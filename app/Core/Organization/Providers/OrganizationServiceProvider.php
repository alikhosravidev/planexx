<?php

declare(strict_types=1);

namespace App\Core\Organization\Providers;

use function app;

use App\Core\Organization\Entities\PersonalAccessToken;
use App\Core\Organization\Http\Middlewares\CheckUserAccessToken;
use App\Core\Organization\Registrars\OrganizationDistributionRegistrar;
use App\Core\Organization\Registrars\OrganizationMenuRegistrar;
use App\Core\Organization\Registrars\OrganizationQuickAccessRegistrar;
use App\Core\Organization\Registrars\OrganizationStatsRegistrar;
use App\Utilities\ProviderUtility;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class OrganizationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);
    }

    public function boot(): void
    {
        app('menu')->registerBy(OrganizationMenuRegistrar::class);
        app('stat')->registerBy(OrganizationStatsRegistrar::class);
        app('quick-access')->registerBy(OrganizationQuickAccessRegistrar::class);
        app('distribution')->registerBy(OrganizationDistributionRegistrar::class);
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        Route::pushMiddlewareToGroup('web', CheckUserAccessToken::class);

        $this->loadRoutesFrom(
            ProviderUtility::corePath('Organization/Routes/V1/admin.php')
        );

        $this->loadRoutesFrom(
            ProviderUtility::corePath('Organization/Routes/V1/client.php')
        );

        $this->loadMigrationsFrom(
            ProviderUtility::corePath('Organization/Database/Migrations')
        );

        $this->loadTranslationsFrom(
            ProviderUtility::corePath('Organization/Resources/lang'),
            'Organization'
        );
    }
}
