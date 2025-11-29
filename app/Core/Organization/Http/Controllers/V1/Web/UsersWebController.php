<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Controllers\V1\Web;

use App\Contracts\Controller\BaseWebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UsersWebController extends BaseWebController
{
    public function index(Request $request): View
    {
        $userType = $request->get('type', '');

        $typeLabels = [
            'employee' => ['title' => 'کارکنان', 'singular' => 'کارمند', 'icon' => 'fa-user-tie'],
            'customer' => ['title' => 'مشتریان', 'singular' => 'مشتری', 'icon' => 'fa-users'],
            'user'     => ['title' => 'کاربران عادی', 'singular' => 'کاربر', 'icon' => 'fa-user'],
        ];

        $pageTitle = isset($typeLabels[$userType]) ? $typeLabels[$userType]['title'] : 'مدیریت کاربران';

        $breadcrumbs = [
            ['label' => 'خانه', 'url' => route('dashboard')],
            ['label' => 'ساختار سازمانی', 'url' => route('org.dashboard')],
            ['label' => $pageTitle],
        ];

        $queryParams = $request->all();

        if ($userType) {
            $queryParams['filter']['user_type'] = $userType;
        }

        $response = $this->forwardToApi('api.v1.admin.org.users.index', $queryParams, 'GET');

        $departments = [];

        if ($userType === 'employee') {
            $deptResponse = $this->forwardToApi('api.v1.admin.org.departments.index', ['per_page' => 100], 'GET');
            $departments  = $deptResponse['data'] ?? [];
        }

        return view('Organization::users.index', [
            'users'       => $response['data']                    ?? [],
            'pagination'  => $response['meta']['pagination'] ?? [],
            'pageTitle'   => $pageTitle,
            'breadcrumbs' => $breadcrumbs,
            'userType'    => $userType,
            'typeLabels'  => $typeLabels,
            'departments' => $departments,
        ]);
    }
}
