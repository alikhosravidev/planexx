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

class UsersWebController extends BaseWebController
{
    public function __construct(
        private readonly EnumTransformer $enumTransformer,
    ) {
    }

    public function index(Request $request): View
    {
        $userType = Str::ucfirst($request->get('type') ?? UserTypeEnum::Employee->name);
        $lowerUserType = Str::lower($userType);

        $userTypes = $this->apiGet('api.v1.admin.enums.show', ['enum' => 'UserTypeEnum']);
        $userTypes = collect($userTypes['result'])->keyBy('name');
        $pageTitle = isset($userTypes[$userType]['plural']) ? $userTypes[$userType]['plural'] : 'مدیریت کاربران';

        $breadcrumbs = [
            ['label' => 'خانه', 'url' => route('web.dashboard')],
            ['label' => 'ساختار سازمانی', 'url' => route('web.org.dashboard')],
            ['label' => $pageTitle],
        ];

        $queryParams = $request->all();

        if ($userType) {
            $queryParams['filter']['user_type'] = $userType;
        }

        $departments = [];
        if ($userType === UserTypeEnum::Employee->name) {
            $deptResponse = $this->apiGet(
                'api.v1.admin.org.departments.keyValList',
                ['per_page' => 100, 'field' => 'name']
            );
            $departments  = $deptResponse['result'] ?? [];
        }

        $response = $this->apiGet('api.v1.admin.org.users.index', $queryParams);

        return view("Organization::users.index-{$lowerUserType}", [
            'users'       => $response['result'] ?? [],
            'pagination'  => $response['meta']['pagination'] ?? [],
            'pageTitle'   => $pageTitle,
            'breadcrumbs' => $breadcrumbs,
            'userType'    => $userType,
            'departments' => $departments,
        ]);
    }

    public function show(User $user): View
    {
        $response = $this->apiGet('api.v1.admin.org.users.show', [
            'user' => $user->id,
            'includes' => 'directManager,jobPosition,departments',
        ]);

        return view("Organization::users.show", [
            'user' => $response['result'] ?? [],
        ]);
    }
}
