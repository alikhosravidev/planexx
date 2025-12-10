<?php

declare(strict_types=1);

namespace App\Core\BPMS\Http\Controllers\Web;

use App\Contracts\Controller\BaseWebController;
use App\Services\Distribution\DistributionManager;
use App\Services\QuickAccess\QuickAccessManager;
use App\Services\Stats\StatManager;
use Illuminate\Contracts\View\View;

class BPMSDashboardController extends BaseWebController
{
    public function __construct(
        private readonly StatManager $statManager,
        private readonly QuickAccessManager $quickAccessManager,
        private readonly DistributionManager $distributionManager,
    ) {
    }

    public function index(): View
    {
        $pageTitle   = 'مدیریت وظایف';
        $currentPage = 'bpms-dashboard';

        $breadcrumbs = [
            ['label' => 'داشبورد', 'url' => route('web.dashboard')],
            ['label' => $pageTitle],
        ];

        $stats              = $this->statManager->getTransformed('bpms.dashboard.stats');
        $quickAccessModules = $this->quickAccessManager->getTransformed('bpms.dashboard.quick-access');

        $topWorkflows     = $this->statManager->getTransformed('bpms.dashboard.top-workflows');
        $taskDistribution = $this->distributionManager->getTransformed('bpms.dashboard.task-distribution');

        return view(
            'BPMS::dashboard.index',
            compact(
                'pageTitle',
                'currentPage',
                'breadcrumbs',
                'stats',
                'quickAccessModules',
                'topWorkflows',
                'taskDistribution',
            )
        );
    }
}
