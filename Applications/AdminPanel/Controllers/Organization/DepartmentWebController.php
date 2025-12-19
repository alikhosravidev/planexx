<?php

declare(strict_types=1);

namespace Applications\AdminPanel\Controllers\Organization;

use App\Core\Organization\Entities\Department;
use App\Services\Stats\StatManager;
use Applications\Contracts\BaseWebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DepartmentWebController extends BaseWebController
{
    public function __construct(
        private readonly StatManager $statManager,
    ) {
    }

    public function index(Request $request): View
    {
        $pageTitle = 'مدیریت دپارتمان‌ها';
        $response  = $this->apiGet(
            'api.v1.admin.org.departments.index',
            [
                'filter'    => ['parent_id' => null],
                'includes'  => 'children,thumbnail,manager',
                'withCount' => 'users:employees_count,children.users:employees_count',
            ],
        );

        return view('panel::departments.index', [
            'departments' => $response['result']             ?? [],
            'pagination'  => $response['meta']['pagination'] ?? [],
            'pageTitle'   => $pageTitle,
            // TODO: Refactor stats (get data from API)
            'stats' => $this->statManager->getTransformed('org.department.stats'),
        ]);
    }

    public function create(): View
    {
        return view('panel::departments.add-or-edit');
    }

    public function edit(Department $department): View
    {
        $response = $this->apiGet('api.v1.admin.org.departments.show', [
            'department' => $department->id,
            'includes'   => 'parent,manager,thumbnail',
        ]);

        return view('panel::departments.add-or-edit', [
            'department' => $response['result'] ?? [],
        ]);
    }

    public function chart(Request $request): View
    {
        $pageTitle = 'چارت سازمانی';

        // Use the optimized chart endpoint that handles recursive loading
        // and avoids N+1 queries regardless of the nesting depth
        // TODO: remove repetitive keys (children_with_relations, children, childrenWithUsers)
        $response = $this->apiGet(
            'api.v1.admin.org.departments.index',
            [
                'filter'    => ['parent_id' => null],
                'includes'  => 'childrenWithUsers,thumbnail,users.avatar,manager',
                'withCount' => 'users:employees_count,children.users:employees_count',
            ],
        );

        return view('panel::departments.chart', [
            'departments' => $response['result'] ?? [],
            'pageTitle'   => $pageTitle,
        ]);
    }
}
