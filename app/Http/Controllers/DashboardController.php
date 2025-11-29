<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\Controller\BaseWebController;
use Illuminate\Contracts\View\View;

class DashboardController extends BaseWebController
{
    public function index(): View
    {
        $stats = [
            [
                'title'      => 'کاربران',
                'value'      => '۲۴۸',
                'change'     => '+۱۲٪',
                'changeType' => 'increase',
                'icon'       => 'fa-solid fa-users',
                'color'      => 'blue',
            ],
            [
                'title'      => 'کارکنان',
                'value'      => '۱۵۶',
                'change'     => '+۸٪',
                'changeType' => 'increase',
                'icon'       => 'fa-solid fa-user-tie',
                'color'      => 'green',
            ],
            [
                'title'      => 'تجربه‌ها',
                'value'      => '۱۸۴',
                'change'     => '+۲۳٪',
                'changeType' => 'increase',
                'icon'       => 'fa-solid fa-lightbulb',
                'color'      => 'yellow',
            ],
            [
                'title'      => 'مشتریان',
                'value'      => '۳۸۹',
                'change'     => '-۳٪',
                'changeType' => 'decrease',
                'icon'       => 'fa-solid fa-handshake',
                'color'      => 'orange',
            ],
        ];

        $quickAccessModules = [
            [
                'title'   => 'ساختار سازمانی',
                'icon'    => 'fa-solid fa-sitemap',
                'color'   => 'blue',
                'url'     => route('dashboard'),
                'enabled' => true,
            ],
            [
                'title'   => 'مدیریت اسناد و فایل‌ها',
                'icon'    => 'fa-solid fa-folder-open',
                'color'   => 'amber',
                'url'     => route('dashboard'),
                'enabled' => true,
            ],
            [
                'title'   => 'مدیریت وظایف',
                'icon'    => 'fa-solid fa-list-check',
                'color'   => 'indigo',
                'url'     => route('dashboard'),
                'enabled' => true,
            ],
            [
                'title'   => 'پایگاه تجربه سازمانی',
                'icon'    => 'fa-solid fa-book',
                'color'   => 'teal',
                'url'     => route('dashboard'),
                'enabled' => true,
            ],
            [
                'title'   => 'مالی و وصول مطالبات',
                'icon'    => 'fa-solid fa-coins',
                'color'   => 'green',
                'url'     => route('dashboard'),
                'enabled' => true,
            ],
            [
                'title'   => 'CRM',
                'icon'    => 'fa-solid fa-users-line',
                'color'   => 'purple',
                'url'     => route('dashboard'),
                'enabled' => true,
            ],
        ];

        $breadcrumbs = [
            ['label' => 'داشبورد'],
        ];

        return view('dashboard.index', [
            'stats'              => $stats,
            'quickAccessModules' => $quickAccessModules,
            'breadcrumbs'        => $breadcrumbs,
        ]);
    }
}
