<?php

declare(strict_types=1);

namespace App\Core\Organization\Providers;

use App\Core\Organization\Entities\PersonalAccessToken;
use App\Core\Organization\Http\Middlewares\CheckUserAccessToken;
use App\Services\Menu\MenuBuilder;
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
        $this->registerMenu();
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

    protected function registerMenu(): void
    {
        app('menu')->register('dashboard.sidebar', function (MenuBuilder $menu) {
            $menu->item('ساختار سازمانی', 'org')
                ->route('org.dashboard')
                ->icon('fa-solid fa-sitemap')
                ->order(2);
        });

        app('menu')->register('org.sidebar', function (MenuBuilder $menu) {
            $menu->item('داشبورد ماژول', 'org')
                ->route('org.dashboard')
                ->icon('fa-solid fa-chart-pie')
                ->order(1);

            $menu->item('کارکنان', 'org-employees')
                ->route('org.users.index', ['type' => 'employee'])
                ->icon('fa-solid fa-user-tie')
                ->order(2);

            $menu->item('مشتریان', 'org-customers')
                ->route('org.users.index', ['type' => 'customer'])
                ->icon('fa-solid fa-users')
                ->order(3);

            $menu->item('کاربران عادی', 'org-regular-users')
                ->route('org.users.index', ['type' => 'user'])
                ->icon('fa-solid fa-user')
                ->order(4);

            $menu->item('دپارتمان‌ها', 'org-departments')
                ->url('/dashboard/org/departments/list.php')
                ->icon('fa-solid fa-sitemap')
                ->order(5);

            $menu->item('نقش‌ها و دسترسی‌ها', 'org-roles')
                ->url('/dashboard/org/roles-permissions/roles.php')
                ->icon('fa-solid fa-shield-halved')
                ->order(6);
        });
    }
}
