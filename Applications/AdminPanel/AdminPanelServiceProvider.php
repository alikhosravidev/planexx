<?php

declare(strict_types=1);

namespace Applications\AdminPanel;

use Illuminate\Support\ServiceProvider;

class AdminPanelServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadViewsFrom(
            base_path('Applications/AdminPanel/Views'),
            'panel'
        );
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }
}
