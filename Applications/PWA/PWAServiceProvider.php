<?php

declare(strict_types=1);

namespace Applications\PWA;

use Illuminate\Support\ServiceProvider;

class PWAServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->loadViewsFrom(
            base_path('Applications/PWA/Resources/views'),
            'pwa'
        );

        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }
}
