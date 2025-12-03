<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Controllers\Web;

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
                'includes'  => 'children',
                'withCount' => 'users:employees_count,children.users:employees_count',
            ],
        );

        return view('Organization::departments.index', [
            'departments' => $response['result']             ?? [],
            'pagination'  => $response['meta']['pagination'] ?? [],
            'pageTitle'   => $pageTitle,
        ]);
    }

    public function show(Department $department): View
    {
        $response = $this->apiGet('api.v1.admin.org.departments.show', [
            'department' => $department->id,
            'includes'   => 'parent,manager',
        ]);

        return view('Organization::departments.show', [
            'department' => $response['result'] ?? [],
        ]);
    }

    public function create(): View
    {
        $deptResponse = $this->apiGet(
            'api.v1.admin.org.departments.keyValList',
            ['per_page' => 100, 'field' => 'name', 'filter' => ['parent_id' => null]]
        );

        $usersResponse = $this->apiGet(
            'api.v1.admin.org.users.keyValList',
            ['per_page' => 100, 'field' => 'full_name', 'filter' => ['user_type' => UserTypeEnum::Employee]]
        );

        return view('Organization::departments.add-or-edit', [
            'parentDepartments' => $deptResponse['result']  ?? [],
            'managers'          => $usersResponse['result'] ?? [],
        ]);
    }

    public function edit(Department $department): View
    {
        $response = $this->apiGet('api.v1.admin.org.departments.show', [
            'department' => $department->id,
            'includes'   => 'parent,manager',
        ]);

        $deptResponse = $this->apiGet(
            'api.v1.admin.org.departments.keyValList',
            ['per_page' => 100, 'field' => 'name']
        );

        $usersResponse = $this->apiGet(
            'api.v1.admin.org.users.keyValList',
            ['per_page' => 100, 'field' => 'full_name', 'filter' => ['user_type' => UserTypeEnum::Employee]]
        );

        return view('Organization::departments.add-or-edit', [
            'department'        => $response['result']      ?? [],
            'parentDepartments' => $deptResponse['result']  ?? [],
            'managers'          => $usersResponse['result'] ?? [],
        ]);
    }
}
