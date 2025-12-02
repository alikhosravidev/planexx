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

        $departments = [];

        if ($userType === UserTypeEnum::Employee) {
            $deptResponse = $this->apiGet(
                'api.v1.admin.org.departments.keyValList',
                ['per_page' => 100, 'field' => 'name']
            );
            $departments = $deptResponse['result'] ?? [];
        }

        $filters              = [];
        $filters['user_type'] = $userType->value;

        if ($request->filled('status')) {
            $filters['is_active'] = $request->get('status') === 'active' ? 1 : 0;
        }

        if ($request->filled('department_id')) {
            $filters['departments.id'] = $request->get('department_id');
        }

        $queryParams           = $request->except('filter');
        $queryParams['filter'] = $filters;

        $response = $this->apiGet('api.v1.admin.org.users.index', $queryParams);

        return view("Organization::users.index-{$lowerUserType}", [
            'users'       => $response['result']             ?? [],
            'pagination'  => $response['meta']['pagination'] ?? [],
            'pageTitle'   => $pageTitle,
            'userType'    => $userType,
            'departments' => $departments,
            'currentPage' => "org-{$lowerUserType}",
        ]);
    }

    public function show(User $user): View
    {
        $response = $this->apiGet('api.v1.admin.org.users.show', [
            'user'     => $user->id,
            'includes' => 'directManager,jobPosition,departments',
        ]);

        return view('Organization::users.show', [
            'user' => $response['result'] ?? [],
        ]);
    }

    public function create(User $user): View
    {
        return view('Organization::users.add-or-edit');
    }

    public function edit(User $user): View
    {
        $response = $this->apiGet('api.v1.admin.org.users.show', [
            'user'     => $user->id,
            'includes' => 'directManager,jobPosition,departments',
        ]);

        return view('Organization::users.add-or-edit', [
            'user' => $response['result'] ?? [],
        ]);
    }
}
