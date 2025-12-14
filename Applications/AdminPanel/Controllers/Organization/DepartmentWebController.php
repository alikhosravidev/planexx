<?php

declare(strict_types=1);

namespace Applications\AdminPanel\Controllers\Organization;

use App\Contracts\Controller\BaseWebController;
use App\Core\Organization\Entities\Department;
use App\Core\Organization\Enums\UserTypeEnum;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DepartmentWebController extends BaseWebController
{
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

        return view('Organization::departments.index', [
            'departments' => $response['result']             ?? [],
            'pagination'  => $response['meta']['pagination'] ?? [],
            'pageTitle'   => $pageTitle,
        ]);
    }

    public function create(): View
    {
        $deptResponse = $this->apiGet(
            'api.v1.admin.org.departments.index',
            ['per_page' => 100, 'field' => 'name', 'filter' => ['parent_id' => null], 'includes' => 'children'],
        );

        $usersResponse = $this->apiGet(
            'api.v1.admin.org.users.keyValList',
            ['per_page' => 100, 'field' => 'full_name', 'filter' => ['user_type' => UserTypeEnum::Employee]]
        );

        $typeResponse = $this->apiGet('api.v1.admin.enums.keyValList', ['enum' => 'DepartmentTypeEnum']);

        return view('Organization::departments.add-or-edit', [
            'allDepartments'  => $deptResponse['result']  ?? [],
            'managers'        => $usersResponse['result'] ?? [],
            'departmentTypes' => $typeResponse['result']  ?? [],
        ]);
    }

    public function edit(Department $department): View
    {
        $response = $this->apiGet('api.v1.admin.org.departments.show', [
            'department' => $department->id,
            'includes'   => 'parent,manager,thumbnail',
        ]);

        $deptResponse = $this->apiGet(
            'api.v1.admin.org.departments.index',
            ['per_page' => 100, 'field' => 'name', 'filter' => ['parent_id' => null], 'includes' => 'children'],
        );

        $usersResponse = $this->apiGet(
            'api.v1.admin.org.users.keyValList',
            ['per_page' => 100, 'field' => 'full_name', 'filter' => ['user_type' => UserTypeEnum::Employee]]
        );

        $typeResponse = $this->apiGet('api.v1.admin.enums.keyValList', ['enum' => 'DepartmentTypeEnum']);

        return view('Organization::departments.add-or-edit', [
            'department'      => $response['result']      ?? [],
            'allDepartments'  => $deptResponse['result']  ?? [],
            'managers'        => $usersResponse['result'] ?? [],
            'departmentTypes' => $typeResponse['result']  ?? [],
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

        return view('Organization::departments.chart', [
            'departments' => $response['result'] ?? [],
            'pageTitle'   => $pageTitle,
        ]);
    }
}
