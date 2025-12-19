<?php

declare(strict_types=1);

namespace Applications\AdminPanel\Controllers\Organization;

use App\Core\Organization\Entities\Role;
use Applications\Contracts\BaseWebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class RoleWebController extends BaseWebController
{
    public function index(Request $request): View
    {
        $pageTitle = 'مدیریت نقش‌ها';
        $response  = $this->apiGet(
            'api.v1.admin.org.roles.index',
            [
                'withCount' => 'users,permissions',
            ],
        );

        return view('panel::roles.index', [
            'roles'      => $response['result']             ?? [],
            'pagination' => $response['meta']['pagination'] ?? [],
            'pageTitle'  => $pageTitle,
        ]);
    }

    public function show(Role $role): View
    {
        $response = $this->apiGet('api.v1.admin.org.roles.show', [
            'role'     => $role->id,
            'includes' => 'permissions',
        ]);

        return view('panel::roles.show', [
            'role' => $response['result'] ?? [],
        ]);
    }

    public function create(): View
    {
        $permissionsResponse = $this->apiGet(
            'api.v1.admin.org.permissions.index',
            ['per_page' => 200]
        );

        return view('panel::roles.add-or-edit', [
            'permissions' => $permissionsResponse['result'] ?? [],
        ]);
    }

    public function edit(Role $role): View
    {
        $response = $this->apiGet('api.v1.admin.org.roles.show', [
            'role'     => $role->id,
            'includes' => 'permissions',
        ]);

        $permissionsResponse = $this->apiGet(
            'api.v1.admin.org.permissions.index',
            ['per_page' => 200]
        );

        return view('panel::roles.add-or-edit', [
            'role'        => $response['result']            ?? [],
            'permissions' => $permissionsResponse['result'] ?? [],
        ]);
    }

    public function permissions(Role $role): View
    {
        $response = $this->apiGet('api.v1.admin.org.roles.show', [
            'role'     => $role->id,
            'includes' => 'permissions',
        ]);

        $permissionsResponse = $this->apiGet(
            'api.v1.admin.org.permissions.index',
            ['per_page' => 200]
        );

        return view('panel::roles.permissions', [
            'role'        => $response['result']            ?? [],
            'permissions' => $permissionsResponse['result'] ?? [],
        ]);
    }
}
