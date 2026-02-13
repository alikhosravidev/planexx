<?php

declare(strict_types=1);

namespace Modules\Product\Providers;

use App\Utilities\ProviderUtility;
use Illuminate\Support\ServiceProvider;
use Modules\Product\Registrars\CategoryStatsRegistrar;
use Modules\Product\Registrars\ProductMenuRegistrar;
use Modules\Product\Registrars\ProductQuickAccessRegistrar;
use Modules\Product\Registrars\ProductStatsRegistrar;

class ProductServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        app('stat')->registerBy(ProductStatsRegistrar::class);
        app('stat')->registerBy(CategoryStatsRegistrar::class);
        app('quick-access')->registerBy(ProductQuickAccessRegistrar::class);
        app('menu')->registerBy(ProductMenuRegistrar::class);

        $this->loadRoutesFrom(
            ProviderUtility::modulePath('Product/Routes/V1/admin.php')
        );

        $this->loadRoutesFrom(
            ProviderUtility::modulePath('Product/Routes/V1/client.php')
        );

        $this->loadMigrationsFrom(
            ProviderUtility::modulePath('Product/Database/Migrations')
        );

        $this->loadTranslationsFrom(
            ProviderUtility::modulePath('Product/Resources/lang'),
            'Product'
        );
    }
}
