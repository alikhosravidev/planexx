<?php

declare(strict_types=1);

namespace App\Core\BPMS\Providers;

use App\Core\BPMS\Entities\WorkflowState;
use App\Core\BPMS\Registrars\BPMSDistributionRegistrar;
use App\Core\BPMS\Registrars\BPMSMenuRegistrar;
use App\Core\BPMS\Registrars\BPMSQuickAccessRegistrar;
use App\Core\BPMS\Registrars\BPMSStatsRegistrar;
use App\Core\BPMS\Registrars\BPMSTopWorkflowsRegistrar;
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

        $this->loadRoutesFrom(
            ProviderUtility::corePath('BPMS/Routes/V1/client.php')
        );

        $this->loadMigrationsFrom(
            ProviderUtility::corePath('BPMS/Database/Migrations')
        );

        WorkflowState::observe(GlobalRecordsOrderingObserver::class);

        app('stat')->registerBy(BPMSStatsRegistrar::class);
        app('stat')->registerBy(BPMSTopWorkflowsRegistrar::class);
        app('quick-access')->registerBy(BPMSQuickAccessRegistrar::class);
        app('distribution')->registerBy(BPMSDistributionRegistrar::class);
        app('menu')->registerBy(BPMSMenuRegistrar::class);
    }
}
