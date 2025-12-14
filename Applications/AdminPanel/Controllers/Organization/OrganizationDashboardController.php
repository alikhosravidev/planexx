<?php

declare(strict_types=1);

namespace Applications\AdminPanel\Controllers\Organization;

use App\Contracts\Controller\BaseWebController;
use App\Http\Transformers\Web\ActivityLogTransformer;
use App\Repositories\ActivityLogRepository;
use App\Services\Distribution\DistributionManager;
use App\Services\QuickAccess\QuickAccessManager;
use App\Services\Stats\StatManager;
use Illuminate\Contracts\View\View;

class OrganizationDashboardController extends BaseWebController
{
    public function __construct(
        private readonly StatManager $statManager,
        private readonly QuickAccessManager $quickAccessManager,
        private readonly DistributionManager $distributionManager,
        private readonly ActivityLogRepository $activityLogRepository,
        private readonly ActivityLogTransformer $transformer,
    ) {
    }

    public function index(): View
    {
        $pageTitle   = 'ساختار سازمانی';
        $currentPage = 'org-dashboard';

        $breadcrumbs = [
            ['label' => 'داشبورد', 'url' => route('web.dashboard')],
            ['label' => $pageTitle],
        ];

        $stats              = $this->statManager->getTransformed('org.dashboard.stats');
        $quickAccessModules = $this->quickAccessManager->getTransformed('org.dashboard.quick-access');
        $distribution       = $this->distributionManager->getTransformed('org.dashboard.distribution');

        $activities = $this->transformer->transformCollection(
            $this->activityLogRepository->getOrganizationActivities(4)
        );

        return view(
            'Organization::dashboard.index',
            compact(
                'pageTitle',
                'currentPage',
                'breadcrumbs',
                'stats',
                'quickAccessModules',
                'distribution',
                'activities'
            )
        );
    }
}
