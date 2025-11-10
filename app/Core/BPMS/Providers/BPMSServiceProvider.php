<?php

declare(strict_types=1);

namespace App\Core\BPMS\Providers;

use App\Core\BPMS\Entities\WorkflowState;
use App\Observers\GlobalRecordsOrderingObserver;
use App\Utilities\ProviderUtility;
use Illuminate\Support\ServiceProvider;

class BPMSServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(
            ProviderUtility::corePath('BPMS/Routes/V1/admin.php')
        );

        $this->loadMigrationsFrom(
            ProviderUtility::corePath('BPMS/Database/Migrations')
        );

        WorkflowState::observe(GlobalRecordsOrderingObserver::class);
    }
}
