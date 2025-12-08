<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Controllers\Web;

use App\Contracts\Controller\BaseWebController;
use App\Core\Organization\Entities\User;
use App\Core\Organization\Enums\UserTypeEnum;
use App\Services\Transformer\FieldTransformers\EnumTransformer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserWebController extends BaseWebController
{
    public function __construct(
        private readonly EnumTransformer $enumTransformer,
    ) {
    }

    public function index(Request $request): View
    {
        $userType      = Str::ucfirst($request->get('user_type') ?? UserTypeEnum::Employee->name);
        $lowerUserType = Str::lower($userType);
        $userType      = UserTypeEnum::fromName($userType);

        $userTypes = $this->apiGet('api.v1.admin.enums.show', ['enum' => 'UserTypeEnum']);
        $userTypes = collect($userTypes['result'])->keyBy('name');
        $pageTitle = isset($userTypes[$userType->name]['plural'])
            ? $userTypes[$userType->name]['plural']
            : 'مدیریت کاربران';

        if ($userType === UserTypeEnum::Employee) {
            $deptResponse = $this->apiGet(
                'api.v1.admin.org.departments.keyValList',
                ['per_page' => 100, 'field' => 'name']
            );
            $rolesResponse = $this->apiGet(
                'api.v1.admin.org.roles.keyValList',
                ['per_page' => 100, 'field' => 'title']
            );
        }

        $filters              = [];
        $filters['user_type'] = $userType->value;

        if ($request->filled('status')) {
            $filters['is_active'] = $request->get('status') === 'active' ? 1 : 0;
        }

        if ($request->filled('department_id')) {
            $filters['departments.id'] = $request->get('department_id');
        }

        $queryParams              = $request->except('filter');
        $queryParams['filter']    = $filters;
        $queryParams['includes']  = 'avatar,roles';
        $queryParams['withCount'] = 'roles.users,roles.permissions';

        $response = $this->apiGet('api.v1.admin.org.users.index', $queryParams);

        return view("Organization::users.index-{$lowerUserType}", [
            'users'       => $response['result']             ?? [],
            'pagination'  => $response['meta']['pagination'] ?? [],
            'pageTitle'   => $pageTitle,
            'userType'    => $userType,
            'departments' => $deptResponse['result'] ?? [],
            'currentPage' => "org-{$lowerUserType}",
            'roles'       => $rolesResponse['result'] ?? [],
        ]);
    }

    public function show(User $user, Request $request): View
    {
        $response = $this->apiGet('api.v1.admin.org.users.show', [
            'user'     => $user->id,
            'includes' => 'directManager,departments',
        ]);

        return view('Organization::users.show', [
            'user' => $response['result'] ?? [],
        ]);
    }

    public function create(Request $request): View
    {
        $userType = UserTypeEnum::fromName(
            Str::ucfirst($request->get('user_type') ?? UserTypeEnum::User->name)
        );
        $userTypeValue = $userType->value;
        $userType      = $userType->name;

        $typeResponse = $this->apiGet('api.v1.admin.enums.keyValList', ['enum' => 'UserTypeEnum']);

        $deptResponse  = ['result' => []];
        $usersResponse = ['result' => []];

        if ($userType === UserTypeEnum::Employee->name) {
            $deptResponse = $this->apiGet(
                'api.v1.admin.org.departments.index',
                ['per_page' => 100, 'field' => 'name', 'filter' => ['parent_id' => null], 'includes' => 'children'],
            );

            $usersResponse = $this->apiGet(
                'api.v1.admin.org.users.keyValList',
                ['per_page' => 100, 'field' => 'full_name', 'filter' => ['user_type' => UserTypeEnum::Employee->value]]
            );
        }

        return view('Organization::users.add-or-edit', [
            'userType'       => $userType,
            'userTypeValue'  => $userTypeValue,
            'userTypes'      => $typeResponse['result']  ?? [],
            'allDepartments' => $deptResponse['result']  ?? [],
            'users'          => $usersResponse['result'] ?? [],
        ]);
    }

    public function edit(User $user): View
    {
        $response = $this->apiGet('api.v1.admin.org.users.show', [
            'user'     => $user->id,
            'includes' => 'directManager,avatar,departments',
        ]);

        $typeResponse = $this->apiGet('api.v1.admin.enums.keyValList', ['enum' => 'UserTypeEnum']);

        $deptResponse = $this->apiGet(
            'api.v1.admin.org.departments.index',
            ['per_page' => 100, 'field' => 'name', 'filter' => ['parent_id' => null], 'includes' => 'children'],
        );

        $usersResponse = $this->apiGet(
            'api.v1.admin.org.users.keyValList',
            ['per_page' => 100, 'field' => 'full_name', 'filter' => ['user_type' => UserTypeEnum::Employee->value]]
        );
        $userType      = $user->user_type->name;
        $userTypeValue = $user->user_type->value;

        return view('Organization::users.add-or-edit', [
            'user'           => $response['result']      ?? [],
            'allDepartments' => $deptResponse['result']  ?? [],
            'users'          => $usersResponse['result'] ?? [],
            'userTypes'      => $typeResponse['result']  ?? [],
            'userType'       => $userType,
            'userTypeValue'  => $userTypeValue,
        ]);
    }
}
