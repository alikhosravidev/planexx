<?php

declare(strict_types=1);

namespace Applications\AdminPanel;

use Illuminate\Support\ServiceProvider;

class AdminPanelServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->loadViewsFrom(
            base_path('Applications/AdminPanel/Resources/views'),
            'panel'
        );

        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }
}
