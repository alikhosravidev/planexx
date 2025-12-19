<?php

declare(strict_types=1);

namespace Applications\AdminPanel\Controllers\BPMS;

use App\Core\BPMS\Entities\Workflow;
use App\Core\Organization\Enums\UserTypeEnum;
use Applications\Contracts\BaseWebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class WorkflowWebController extends BaseWebController
{
    public function index(Request $request): View
    {
        $pageTitle = 'مدیریت فرایندها';

        $queryParams = $request->except('filter');
        $filters     = [];

        if ($request->filled('status')) {
            $filters['is_active'] = $request->get('status') === 'active' ? 1 : 0;
        }

        if ($request->filled('department_id')) {
            $filters['department_id'] = $request->get('department_id');
        }
        $queryParams['includes'] = 'department,owner,states';
        $queryParams['filter']   = $filters;

        $response = $this->apiGet('api.v1.admin.bpms.workflows.index', $queryParams);

        $deptResponse = $this->apiGet(
            'api.v1.admin.org.departments.index',
            ['per_page' => 100, 'field' => 'name', 'filter' => ['parent_id' => null], 'includes' => 'children'],
        );

        return view('panel::workflows.index', [
            'workflows'   => $response['result']             ?? [],
            'pagination'  => $response['meta']['pagination'] ?? [],
            'departments' => $deptResponse['result']         ?? [],
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

        $rolesResponse = $this->apiGet(
            'api.v1.admin.org.roles.keyValList',
            ['per_page' => 100, 'field' => 'title']
        );

        return view('panel::workflows.add-or-edit', [
            'departments' => $deptResponse['result']  ?? [],
            'users'       => $usersResponse['result'] ?? [],
            'roles'       => $rolesResponse['result'] ?? [],
        ]);
    }

    public function edit(Workflow $workflow): View
    {
        $workflowResponse = $this->apiGet('api.v1.admin.bpms.workflows.show', [
            'workflow' => $workflow->id,
            'includes' => 'states,allowedRoles',
        ]);

        $deptResponse = $this->apiGet(
            'api.v1.admin.org.departments.index',
            ['per_page' => 100, 'field' => 'name', 'filter' => ['parent_id' => null], 'includes' => 'children'],
        );

        $usersResponse = $this->apiGet(
            'api.v1.admin.org.users.keyValList',
            ['per_page' => 100, 'field' => 'full_name']
        );

        $rolesResponse = $this->apiGet(
            'api.v1.admin.org.roles.keyValList',
            ['per_page' => 100, 'field' => 'title']
        );

        return view('panel::workflows.add-or-edit', [
            'workflow'    => $workflowResponse['result'] ?? [],
            'departments' => $deptResponse['result']     ?? [],
            'users'       => $usersResponse['result']    ?? [],
            'roles'       => $rolesResponse['result']    ?? [],
        ]);
    }
}
