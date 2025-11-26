<?php

declare(strict_types=1);

namespace App\Core\Organization\Providers;

use App\Contracts\User\UserRepositoryInterface;
use App\Core\Organization\Entities\PersonalAccessToken;
use App\Core\Organization\Http\Middlewares\CheckUserAccessToken;
use App\Core\Organization\Repositories\UserRepository;
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

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        Route::pushMiddlewareToGroup('web', CheckUserAccessToken::class);

        $this->loadRoutesFrom(
            ProviderUtility::corePath('Organization/Routes/v1/admin.php')
        );

        $this->loadRoutesFrom(
            ProviderUtility::corePath('Organization/Routes/v1/web.php')
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
