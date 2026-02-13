<?php

declare(strict_types=1);

namespace Applications\AdminPanel\Controllers\Product;

use App\Services\QuickAccess\QuickAccessManager;
use App\Services\Stats\StatManager;
use Applications\Contracts\BaseWebController;
use Illuminate\Contracts\View\View;

class PanelProductDashboardController extends BaseWebController
{
    public function __construct(
        private readonly StatManager $statManager,
        private readonly QuickAccessManager $quickAccessManager,
    ) {
    }

    public function index(): View
    {
        $pageTitle   = 'داشبورد محصولات و لیست‌ها';
        $currentPage = 'product-dashboard';

        $breadcrumbs = [
            ['label' => 'داشبورد', 'url' => '#'],
            ['label' => $pageTitle],
        ];

        // TODO: get stats, quick access and distribution from API.
        $stats              = $this->statManager->getTransformed('product.dashboard.stats');
        $quickAccessModules = $this->quickAccessManager->getTransformed('product.dashboard.quick-access');

        return view(
            'panel::dashboard.product',
            compact(
                'pageTitle',
                'currentPage',
                'breadcrumbs',
                'stats',
                'quickAccessModules',
            )
        );
    }
}
