<?php

declare(strict_types=1);

namespace Applications\PWA\Controllers;

use App\Services\QuickAccess\QuickAccessManager;
use App\Services\Stats\StatManager;
use Applications\Contracts\BaseWebController;
use Illuminate\Contracts\View\View;

class PWADashboardController extends BaseWebController
{
    public function __construct(
        private readonly StatManager        $statManager,
        private readonly QuickAccessManager $quickAccessManager,
    ) {
    }

    public function index(): View
    {
        $stats              = $this->statManager->getTransformed('pwa.dashboard.stats');
        $quickAccessModules = $this->quickAccessManager->getTransformed('pwa.dashboard.quick-access');

        $breadcrumbs = [
            ['label' => 'داشبورد'],
        ];

        return view('pwa::dashboard.index', [
            'stats'              => $stats,
            'quickAccessModules' => $quickAccessModules,
            'breadcrumbs'        => $breadcrumbs,
        ]);
    }
}
