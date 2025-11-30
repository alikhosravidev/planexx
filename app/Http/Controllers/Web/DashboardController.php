<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Contracts\Controller\BaseWebController;
use App\Services\QuickAccess\QuickAccessManager;
use App\Services\Stats\StatManager;
use Illuminate\Contracts\View\View;

class DashboardController extends BaseWebController
{
    public function __construct(
        private readonly StatManager        $statManager,
        private readonly QuickAccessManager $quickAccessManager,
    ) {
    }

    public function index(): View
    {
        $stats              = $this->statManager->getTransformed('dashboard.stats');
        $quickAccessModules = $this->quickAccessManager->getTransformed('dashboard.quick-access');

        $breadcrumbs = [
            ['label' => 'داشبورد'],
        ];

        return view('dashboard.index', [
            'stats'              => $stats,
            'quickAccessModules' => $quickAccessModules,
            'breadcrumbs'        => $breadcrumbs,
        ]);
    }
}
