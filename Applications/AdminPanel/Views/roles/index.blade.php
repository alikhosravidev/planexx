@php
    $title = 'مدیریت نقش‌ها';
    $currentPage = 'org-roles';

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'ساختار سازمانی', 'url' => route('web.org.dashboard')],
        ['label' => $title],
    ];

    $actionButtons = [
        ['label' => 'افزودن نقش جدید', 'url' => route('web.org.roles.create'), 'icon' => 'fa-solid fa-plus', 'type' => 'primary'],
    ];

    $columns = [
        [
            'key' => 'title',
            'label' => 'نام نقش',
            'component' => 'text',
            'options' => [
                'icon' => 'fa-solid fa-shield-halved',
                'icon_class' => 'text-primary',
            ],
        ],
        [
            'key' => 'users_count',
            'label' => 'تعداد کاربران',
            'component' => 'badge',
            'options' => [
                'variant' => 'info',
                'size' => 'sm',
                'icon' => 'fa-solid fa-users',
                'suffix' => 'نفر',
            ],
        ],
        [
            'key' => 'permissions_count',
            'label' => 'تعداد دسترسی‌ها',
            'component' => 'badge',
            'options' => [
                'variant' => 'success',
                'size' => 'sm',
                'icon' => 'fa-solid fa-key',
                'suffix' => 'دسترسی',
            ],
        ],
        [
            'key' => 'created_at.human.short',
            'label' => 'تاریخ ایجاد',
            'component' => 'text',
        ],
    ];

    $actions = [
        [
            'icon' => 'fa-key',
            'type' => 'link',
            'tooltip' => 'مدیریت دسترسی‌ها',
            'url' => function($row) {
                return route('web.org.roles.permissions', ['role' => $row['id']]);
            },
        ],
        [
            'icon' => 'fa-pen',
            'type' => 'link',
            'tooltip' => 'ویرایش',
            'url' => function($row) {
                return route('web.org.roles.edit', ['role' => $row['id']]);
            },
        ],
        [
            'icon' => 'fa-trash',
            'type' => 'button',
            'variant' => 'danger',
            'tooltip' => 'حذف',
            'data_attrs' => [
                'data-ajax' => '',
                'data-confirm' => 'آیا از حذف این نقش اطمینان دارید؟',
                'data-action' => function($row) {
                    return route('api.v1.admin.org.roles.destroy', ['role' => $row['id']]);
                },
                'data-method' => 'DELETE',
                'data-on-success' => 'reload'
            ],
        ],
    ];
@endphp

<x-panel::layouts.app :title="$title">
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
                :title="$title"
                :breadcrumbs="$breadcrumbs"
                :actions="$actionButtons"
            />

            <div class="flex-1 p-6 lg:p-8">
                <div class="bg-white border border-border-light rounded-2xl overflow-hidden">
                    <x-panel::ui.table.auto
                        :columns="$columns"
                        :data="$roles"
                        :actions="$actions"
                        empty-icon="fa-shield-halved"
                        empty-message="هیچ نقشی یافت نشد"
                    />

                    @if(!empty($roles) && !empty($pagination))
                        @php
                            $currentPage = $pagination['current_page'] ?? 1;
                            $lastPage = $pagination['last_page'] ?? 1;
                        @endphp
                        <x-panel::ui.pagination
                            :from="$pagination['from'] ?? 1"
                            :to="$pagination['to'] ?? count($roles)"
                            :total="$pagination['total'] ?? count($roles)"
                            label="نقش"
                            :current="$currentPage"
                            :prevUrl="request()->fullUrlWithQuery(['page' => $currentPage - 1])"
                            :nextUrl="request()->fullUrlWithQuery(['page' => $currentPage + 1])"
                            :hasPrev="$currentPage > 1"
                            :hasNext="$currentPage < $lastPage"
                        />
                    @endif
                </div>
            </div>
        </main>
    </div>
</x-panel::layouts.app>
