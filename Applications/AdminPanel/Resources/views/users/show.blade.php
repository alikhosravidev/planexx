@php
    $userType = $user['user_type']['name'] ?? 'User';
    $userTypeLower = strtolower($userType);
    $typeLabels = [
        'Employee' => ['title' => 'کارکنان', 'singular' => 'کارمند', 'viewTitle' => 'جزئیات کارمند'],
        'Customer' => ['title' => 'مشتریان', 'singular' => 'مشتری', 'viewTitle' => 'جزئیات مشتری'],
        'User' => ['title' => 'کاربران عادی', 'singular' => 'کاربر', 'viewTitle' => 'جزئیات کاربر'],
    ];

    $pageTitle = isset($typeLabels[$userType]) ? $typeLabels[$userType]['viewTitle'] : 'جزئیات کاربر';
    $listTitle = isset($typeLabels[$userType]) ? $typeLabels[$userType]['title'] : 'مدیریت کاربران';
    $listUrl = route('web.org.users.index', ['type' => strtolower($userType)]);
    $currentPage = "org-{$userTypeLower}";

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'ساختار سازمانی', 'url' => route('web.org.dashboard')],
        ['label' => $listTitle, 'url' => $listUrl],
        ['label' => $pageTitle],
    ];

    $actionButtons = array_values(array_filter([
        isset($user['id']) ? ['label' => 'ویرایش', 'url' => route('web.org.users.edit', ['user' => $user['id']]), 'icon' => 'fa-solid fa-pen', 'type' => 'primary'] : null,
        ['label' => 'بازگشت', 'url' => $listUrl, 'icon' => 'fa-solid fa-arrow-right', 'type' => 'outline'],
    ]));

    $personalInfoItems = [
        ['label' => 'نام کامل', 'value' => $user['full_name'] ?? '-'],
        ['label' => 'شماره موبایل', 'value' => $user['mobile'] ?? '-'],
        ['label' => 'ایمیل', 'value' => $user['email'] ?? '-'],
        ['label' => 'کد ملی', 'value' => $user['national_code'] ?? '-'],
        ['label' => 'جنسیت', 'value' => $user['gender']['label'] ?? '-'],
        ['label' => 'تاریخ تولد', 'value' => $user['birth_date'] ?? '-'],
    ];

    $employmentInfoItems = [];
    if ($userType === 'Employee') {
        $primaryDepartment = collect($user['departments'] ?? [])->firstWhere('pivot.is_primary', true);

        $employmentInfoItems = [
            ['label' => 'کد پرسنلی', 'value' => $user['employee_code'] ?? '-'],
            ['label' => 'موقعیت شغلی', 'value' => $user['job_position']['name'] ?? '-'],
            ['label' => 'دپارتمان', 'value' => $primaryDepartment['name'] ?? '-'],
            ['label' => 'مدیر مستقیم', 'value' => $user['direct_manager']['full_name'] ?? '-'],
            ['label' => 'تاریخ استخدام', 'value' => $user['employment_date'] ?? '-'],
        ];
    }

    $quickActions = [
        /*TODO: develop user actions */
        ['label' => 'بازنشانی رمز عبور', 'icon' => 'fa-solid fa-key', 'variant' => 'default'],
        ['label' => 'ارسال ایمیل', 'icon' => 'fa-solid fa-envelope', 'variant' => 'default'],
        ['label' => 'مسدود کردن کاربر', 'icon' => 'fa-solid fa-ban', 'variant' => 'default'],
        [
            'label' => 'حذف کاربر',
            'icon' => 'fa-solid fa-trash',
            'type' => 'button',
            'variant' => 'danger',
            'data_attrs' => [
                'data-ajax' => '',
                'data-confirm' => 'آیا از حذف این کارمند اطمینان دارید؟',
                'data-action' => function($row) {
                    return route('api.v1.admin.org.users.destroy', ['user' => $row['id']]);
                },
                'data-method' => 'DELETE',
                'data-on-success' => 'reload'
            ],
        ],
    ];
@endphp

<x-panel::layouts.app :title="$pageTitle">
    <div class="flex min-h-screen">
        <x-panel::dashboard.sidebar
            name="org.sidebar"
            :current-page="$currentPage"
            header-variant="back"
            module-title="ساختار سازمانی"
            module-icon="fa-solid fa-sitemap"
        />

        <main class="flex-1 flex flex-col min-w-0">
            <x-panel::dashboard.header
                :title="$pageTitle"
                :breadcrumbs="$breadcrumbs"
                :actions="$actionButtons"
            />

            <div class="flex-1 p-6 lg:p-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <div class="lg:col-span-2 space-y-6">
                        <x-panel::ui.info-section
                            title="اطلاعات شخصی"
                            :items="$personalInfoItems"
                            accent-class="border-primary/20"
                            :columns="2"
                        />

                        @if($userType === 'Employee' && !empty($employmentInfoItems))
                            <x-panel::ui.info-section
                                title="اطلاعات استخدامی"
                                :items="$employmentInfoItems"
                                accent-class="border-green-500/20"
                                :columns="2"
                            />
                        @endif
                    </div>

                    <div class="space-y-6">
                        <x-panel::ui.system-info
                            title="اطلاعات سیستمی"
                            :created-at="$user['created_at']['default'] ?? '-'"
                            :last-login-at="$user['last_login_at']['human']['diff'] ?? '-'"
                            :mobile-verified="!empty($user['mobile_verified_at'])"
                            :email-verified="!empty($user['email_verified_at'])"
                        />

                        <x-panel::ui.quick-actions
                            title="عملیات سریع"
                            :actions="$quickActions"
                            :row="$user"
                        />
                    </div>

                </div>
            </div>
        </main>
    </div>
</x-panel::layouts.app>
