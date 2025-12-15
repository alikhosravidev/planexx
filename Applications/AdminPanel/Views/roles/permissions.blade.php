@php
    $pageTitle = 'مدیریت دسترسی‌های نقش: ' . ($role['title'] ?? $role['name']);
    $currentPage = 'org-roles';

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'ساختار سازمانی', 'url' => route('web.org.dashboard')],
        ['label' => 'مدیریت نقش‌ها', 'url' => route('web.org.roles.index')],
        ['label' => 'دسترسی‌های نقش: ' . ($role['title'] ?? $role['name'])],
    ];

    $actionButtons = [];

    $rolePermissions = collect($role['permissions'] ?? [])->pluck('name')->toArray();

    $modules = [
        [
            'id' => 'app',
            'name' => 'زیرساخت',
            'icon' => 'fa-solid fa-building',
            'color' => 'green',
            'entities' => [
                ['id' => 'tags', 'name' => 'برچسب‌ها'],
            ],
        ],
                [
            'id' => 'org',
            'name' => 'ساختار سازمانی',
            'icon' => 'fa-solid fa-sitemap',
            'color' => 'blue',
            'entities' => [
                ['id' => 'users', 'name' => 'کاربران'],
                ['id' => 'departments', 'name' => 'دپارتمان‌ها'],
                ['id' => 'roles', 'name' => 'نقش‌ها'],
            ],
        ],
        [
            'id' => 'documents',
            'name' => 'مدیریت اسناد و فایل‌ها',
            'icon' => 'fa-solid fa-folder-open',
            'color' => 'amber',
            'entities' => [
                ['id' => 'files', 'name' => 'فایل‌ها'],
                ['id' => 'folders', 'name' => 'پوشه‌ها'],
            ],
        ],
        /*[
            'id' => 'tasks',
            'name' => 'مدیریت وظایف',
            'icon' => 'fa-solid fa-list-check',
            'color' => 'amber',
            'entities' => [
                ['id' => 'tasks', 'name' => 'وظایف'],
                ['id' => 'task_categories', 'name' => 'دسته‌بندی وظایف'],
            ],
        ],
        [
            'id' => 'knowledge',
            'name' => 'پایگاه تجربه سازمانی',
            'icon' => 'fa-solid fa-book',
            'color' => 'teal',
            'entities' => [
                ['id' => 'experiences', 'name' => 'تجربه‌ها'],
                ['id' => 'templates', 'name' => 'قالب‌های تجربه'],
            ],
        ],
        [
            'id' => 'finance',
            'name' => 'مالی و وصول مطالبات',
            'icon' => 'fa-solid fa-coins',
            'color' => 'green',
            'entities' => [
                ['id' => 'invoices', 'name' => 'فاکتورها'],
                ['id' => 'payments', 'name' => 'پرداخت‌ها'],
                ['id' => 'receivables', 'name' => 'مطالبات'],
            ],
        ],
        [
            'id' => 'crm',
            'name' => 'CRM',
            'icon' => 'fa-solid fa-users-line',
            'color' => 'purple',
            'entities' => [
                ['id' => 'customers', 'name' => 'مشتریان'],
                ['id' => 'leads', 'name' => 'سرنخ‌ها'],
                ['id' => 'opportunities', 'name' => 'فرصت‌ها'],
                ['id' => 'contacts', 'name' => 'مخاطبین'],
            ],
        ],*/
    ];

    $standardPermissions = [
        'list' => ['name' => 'مشاهده لیست', 'icon' => 'fa-solid fa-list'],
        'show' => ['name' => 'مشاهده جزئیات', 'icon' => 'fa-solid fa-eye'],
        'store' => ['name' => 'ایجاد', 'icon' => 'fa-solid fa-plus'],
        'update' => ['name' => 'ویرایش', 'icon' => 'fa-solid fa-pen'],
        'delete' => ['name' => 'حذف', 'icon' => 'fa-solid fa-trash'],
        'import' => ['name' => 'ایمپورت داده', 'icon' => 'fa-solid fa-file-import'],
        'export' => ['name' => 'خروجی گرفتن', 'icon' => 'fa-solid fa-file-export'],
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

        <main class="flex-1 flex flex-col">
            <x-panel::dashboard.header
                :title="$pageTitle"
                :breadcrumbs="$breadcrumbs"
                :actions="$actionButtons"
            />

            <div class="flex-1 p-6 lg:p-8">
                <form data-ajax
                      data-method="PUT"
                      action="{{ route('api.v1.admin.org.roles.update', ['role' => $role['id']]) }}"
                      data-on-success="reload"
                      class="space-y-6">
                    @csrf
                    <input type="hidden" name="name" value="{{ $role['name'] }}">

                    <x-panel::organization.permissions.role-header
                        :role-name="($role['title'] ?? $role['name'])"
                        :users-count="($role['users_count'] ?? 0)"
                    />

                    <div class="space-y-6">
                        @foreach($modules as $module)
                            <x-panel::organization.permissions.module-card
                                :module="$module"
                                :standard-permissions="$standardPermissions"
                                :role-permissions="$rolePermissions"
                            />
                        @endforeach
                    </div>

                    <div class="mt-8 bg-bg-primary border border-border-light rounded-2xl p-6">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <p class="text-sm text-text-secondary leading-normal">
                                <i class="fa-solid fa-info-circle ml-1 text-primary"></i>
                                تغییرات دسترسی‌ها بر روی کاربران دارای این نقش اعمال می‌شود.
                            </p>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('web.org.roles.index') }}"
                                   class="bg-bg-secondary text-text-secondary border border-border-medium px-xl py-md rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal">
                                    انصراف
                                </a>
                                <button type="submit"
                                        class="bg-primary text-white px-xl py-md rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 flex items-center gap-2 text-base leading-normal">
                                    <i class="fa-solid fa-check"></i>
                                    <span>ذخیره تغییرات</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>

</x-panel::layouts.app>
