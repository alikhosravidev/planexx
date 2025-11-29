<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Menu\MenuManager;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MenuManager::class, function () {
            return new MenuManager();
        });
        $this->app->alias(MenuManager::class, 'menu');
    }

    public function boot(): void
    {
        $this->registerCoreMenu();
    }

    protected function registerCoreMenu(): void
    {
        app('menu')->register('admin.sidebar', function ($menu) {
            $menu->item('داشبورد', 'dashboard')
                ->route('admin.dashboard')
                ->icon('heroicon-o-home')
                ->order(1);

            $menu->group('تنظیمات', 'settings')
                ->icon('heroicon-o-cog')
                ->order(999)
                ->children(function ($sub) {
                    $sub->item('تنظیمات عمومی')
                        ->route('admin.settings.general')
                        ->permission('settings.general')
                        ->order(1);

                    $sub->item('کاربران')
                        ->route('admin.users.index')
                        ->permission('users.view')
                        ->order(2);
                });
        });
    }
}
