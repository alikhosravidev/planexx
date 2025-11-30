<?php

declare(strict_types=1);

namespace App\Core\Organization\Providers;

use function app;

use App\Core\Organization\Entities\PersonalAccessToken;
use App\Core\Organization\Http\Middlewares\CheckUserAccessToken;
use App\Core\Organization\Registrars\OrganizationMenuRegistrar;
use App\Core\Organization\Registrars\OrgDashboardDistributionRegistrar;
use App\Core\Organization\Registrars\OrgDashboardQuickAccessRegistrar;
use App\Core\Organization\Registrars\OrgDashboardStatsRegistrar;
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
        app('stat')->registerBy(OrgDashboardStatsRegistrar::class);
        app('quick-access')->registerBy(OrgDashboardQuickAccessRegistrar::class);
        app('distribution')->registerBy(OrgDashboardDistributionRegistrar::class);
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        Route::pushMiddlewareToGroup('web', CheckUserAccessToken::class);

        $this->loadRoutesFrom(
            ProviderUtility::corePath('Organization/Routes/API/v1/admin.php')
        );

        $this->loadRoutesFrom(
            ProviderUtility::corePath('Organization/Routes/web.php')
        );

        $this->loadMigrationsFrom(
            ProviderUtility::corePath('Organization/Database/Migrations')
        );

        $this->loadTranslationsFrom(
            ProviderUtility::corePath('Organization/Resources/lang'),
            'Organization'
        );

        $this->loadViewsFrom(
            ProviderUtility::corePath('Organization/Resources/views'),
            'Organization'
        );
    }
}
