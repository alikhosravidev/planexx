@php
    $pageTitle = 'جزئیات دپارتمان';
    $listTitle = 'مدیریت دپارتمان‌ها';
    $listUrl = route('web.org.departments.index');
    $currentPage = 'org-departments';

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'ساختار سازمانی', 'url' => route('web.org.dashboard')],
        ['label' => $listTitle, 'url' => $listUrl],
        ['label' => $pageTitle],
    ];

    $actionButtons = [
        ['label' => 'ویرایش', 'url' => route('web.org.departments.edit', $department['id']), 'icon' => 'fa-solid fa-pen', 'type' => 'primary'],
        ['label' => 'بازگشت', 'url' => $listUrl, 'icon' => 'fa-solid fa-arrow-right', 'type' => 'outline'],
    ];

    $basicInfoItems = [
        ['label' => 'نام دپارتمان', 'value' => $department['name'] ?? '-'],
        ['label' => 'کد دپارتمان', 'value' => $department['code'] ?? '-'],
        ['label' => 'دپارتمان والد', 'value' => $department['parent']['name'] ?? '-'],
        ['label' => 'مدیر دپارتمان', 'value' => $department['manager']['full_name'] ?? '-'],
        ['label' => 'تعداد کارمندان', 'value' => ($department['employees_count'] ?? 0) . ' نفر'],
        ['label' => 'وضعیت', 'value' => $department['is_active'] ? 'فعال' : 'غیرفعال'],
    ];

    $quickActions = [
        [
            'label' => 'حذف دپارتمان',
            'icon' => 'fa-solid fa-trash',
            'type' => 'button',
            'variant' => 'danger',
            'data_attrs' => [
                'data-ajax' => '',
                'data-confirm' => 'آیا از حذف این دپارتمان اطمینان دارید؟',
                'data-action' => route('api.v1.admin.org.departments.destroy', ['department' => $department['id']]),
                'data-method' => 'DELETE',
                'data-on-success' => 'redirect',
                'data-redirect-url' => $listUrl,
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
                            title="اطلاعات پایه"
                            :items="$basicInfoItems"
                            accent-class="border-primary/20"
                            :columns="2"
                        />

                        @if(!empty($department['description']))
                            <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
                                <h2 class="text-lg font-semibold text-text-primary leading-snug mb-4">توضیحات</h2>
                                <p class="text-base text-text-secondary leading-relaxed">{{ $department['description']['full'] }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-6">
                        <x-panel::ui.system-info
                            title="اطلاعات سیستمی"
                            :created-at="$department['created_at']['default'] ?? '-'"
                            :updated-at="$department['updated_at']['default'] ?? '-'"
                        />

                        <x-panel::ui.quick-actions
                            title="عملیات سریع"
                            :actions="$quickActions"
                            :row="$department"
                        />
                    </div>

                </div>
            </div>
        </main>
    </div>
</x-panel::layouts.app>
