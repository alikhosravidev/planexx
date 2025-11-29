<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Controllers\V1\Web;

use App\Contracts\Controller\BaseWebController;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

class OrgDashboardController extends BaseWebController
{
    public function index(): View
    {
        $pageTitle   = 'ساختار سازمانی';
        $currentPage = Route::current()->uri();

        $breadcrumbs = [
            ['label' => 'داشبورد', 'url' => route('dashboard')],
            ['label' => $pageTitle],
        ];

        $stats = [
            [
                'title'      => 'تعداد کاربران',
                'value'      => '۲۴۷',
                'change'     => '+۱۲٪',
                'changeType' => 'increase',
                'icon'       => 'fa-solid fa-users',
                'color'      => 'blue',
            ],
            [
                'title'      => 'کارمندان فعال',
                'value'      => '۱۸۹',
                'change'     => '+۸٪',
                'changeType' => 'increase',
                'icon'       => 'fa-solid fa-user-tie',
                'color'      => 'green',
            ],
            [
                'title'      => 'دپارتمان‌ها',
                'value'      => '۲۴',
                'change'     => '+۲',
                'changeType' => 'increase',
                'icon'       => 'fa-solid fa-sitemap',
                'color'      => 'purple',
            ],
            [
                'title'      => 'موقعیت‌های شغلی',
                'value'      => '۳۸',
                'change'     => '+۵',
                'changeType' => 'increase',
                'icon'       => 'fa-solid fa-briefcase',
                'color'      => 'orange',
            ],
        ];

        $quickAccessModules = [
            [
                'title'   => 'مدیریت کاربران',
                'icon'    => 'fa-solid fa-users',
                'color'   => 'blue',
                'url'     => route('org.dashboard'),
                'enabled' => true,
            ],
            [
                'title'   => 'دپارتمان‌ها',
                'icon'    => 'fa-solid fa-sitemap',
                'color'   => 'purple',
                'url'     => route('org.dashboard'),
                'enabled' => true,
            ],
            [
                'title'   => 'موقعیت‌های شغلی',
                'icon'    => 'fa-solid fa-briefcase',
                'color'   => 'teal',
                'url'     => route('org.dashboard'),
                'enabled' => true,
            ],
            [
                'title'   => 'نقش‌ها و دسترسی‌ها',
                'icon'    => 'fa-solid fa-shield-halved',
                'color'   => 'green',
                'url'     => route('org.dashboard'),
                'enabled' => true,
            ],
        ];

        $distribution = [
            ['label' => 'کارمندان', 'value' => '189 نفر (76%)', 'percent' => 76, 'color' => 'bg-green-500'],
            ['label' => 'مشتریان', 'value' => '48 نفر (19%)', 'percent' => 19, 'color' => 'bg-blue-500'],
            ['label' => 'کاربران عادی', 'value' => '10 نفر (5%)', 'percent' => 5, 'color' => 'bg-gray-400'],
        ];

        $activities = [
            ['icon_bg' => 'bg-green-50', 'icon' => 'fa-solid fa-user-plus text-green-600', 'title' => 'کاربر جدید اضافه شد', 'desc' => 'علی احمدی - دپارتمان فروش', 'time' => '2 ساعت پیش'],
            ['icon_bg' => 'bg-blue-50', 'icon' => 'fa-solid fa-building text-blue-600', 'title' => 'دپارتمان جدید ایجاد شد', 'desc' => 'دپارتمان بازاریابی دیجیتال', 'time' => '5 ساعت پیش'],
            ['icon_bg' => 'bg-purple-50', 'icon' => 'fa-solid fa-shield-halved text-purple-600', 'title' => 'نقش جدید تعریف شد', 'desc' => 'مدیر محصول', 'time' => '1 روز پیش'],
            ['icon_bg' => 'bg-orange-50', 'icon' => 'fa-solid fa-briefcase text-orange-600', 'title' => 'موقعیت شغلی جدید', 'desc' => 'کارشناس ارشد فروش', 'time' => '2 روز پیش'],
        ];

        return view(
            'Organization::dashboard.index',
            compact(
                'pageTitle',
                'currentPage',
                'breadcrumbs',
                'stats',
                'quickAccessModules',
                'distribution',
                'activities'
            )
        );
    }
}
