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

    $moduleColors = [
        'blue' => [
            'bg' => 'bg-blue-50',
            'border' => 'border-blue-200',
            'hover' => 'hover:border-blue-300',
            'icon' => 'text-blue-600',
            'iconBg' => 'bg-blue-100',
            'header' => 'bg-blue-50/80',
            'checkbox' => 'accent-blue-600',
            'headerText' => 'text-blue-700',
        ],
        'amber' => [
            'bg' => 'bg-amber-50',
            'border' => 'border-amber-200',
            'hover' => 'hover:border-amber-300',
            'icon' => 'text-amber-600',
            'iconBg' => 'bg-amber-100',
            'header' => 'bg-amber-50/80',
            'checkbox' => 'accent-amber-600',
            'headerText' => 'text-amber-700',
        ],
        'teal' => [
            'bg' => 'bg-teal-50',
            'border' => 'border-teal-200',
            'hover' => 'hover:border-teal-300',
            'icon' => 'text-teal-600',
            'iconBg' => 'bg-teal-100',
            'header' => 'bg-teal-50/80',
            'checkbox' => 'accent-teal-600',
            'headerText' => 'text-teal-700',
        ],
        'green' => [
            'bg' => 'bg-green-50',
            'border' => 'border-green-200',
            'hover' => 'hover:border-green-300',
            'icon' => 'text-green-600',
            'iconBg' => 'bg-green-100',
            'header' => 'bg-green-50/80',
            'checkbox' => 'accent-green-600',
            'headerText' => 'text-green-700',
        ],
        'purple' => [
            'bg' => 'bg-purple-50',
            'border' => 'border-purple-200',
            'hover' => 'hover:border-purple-300',
            'icon' => 'text-purple-600',
            'iconBg' => 'bg-purple-100',
            'header' => 'bg-purple-50/80',
            'checkbox' => 'accent-purple-600',
            'headerText' => 'text-purple-700',
        ],
    ];
@endphp

<x-layouts.app :title="$pageTitle">
    <div class="flex min-h-screen">
        <x-dashboard.sidebar
            name="org.sidebar"
            :current-page="$currentPage"
            header-variant="back"
            module-title="ساختار سازمانی"
            module-icon="fa-solid fa-sitemap"
        />

        <main class="flex-1 flex flex-col">
            <x-dashboard.header
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

                    <div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center">
                                    <i class="fa-solid fa-shield-halved text-2xl text-primary"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-text-primary leading-snug">{{ $role['title'] ?? $role['name'] }}</h2>
                                    <p class="text-sm text-text-secondary leading-normal mt-1">
                                        <i class="fa-solid fa-users ml-1"></i>
                                        {{ $role['users_count'] ?? 0 }} کاربر با این نقش
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" onclick="selectAllPermissions()"
                                        class="bg-bg-secondary text-text-secondary border border-border-medium px-4 py-2.5 rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-sm leading-normal flex items-center gap-2">
                                    <i class="fa-solid fa-check-double"></i>
                                    <span>انتخاب همه</span>
                                </button>
                                <button type="button" onclick="deselectAllPermissions()"
                                        class="bg-bg-secondary text-text-secondary border border-border-medium px-4 py-2.5 rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-sm leading-normal flex items-center gap-2">
                                    <i class="fa-solid fa-xmark"></i>
                                    <span>حذف انتخاب‌ها</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        @foreach($modules as $module)
                            @php
                                $colors = $moduleColors[$module['color']] ?? $moduleColors['blue'];
                            @endphp

                            <div class="bg-bg-primary border {{ $colors['border'] }} rounded-2xl overflow-hidden {{ $colors['hover'] }} transition-all duration-200">
                                <div class="{{ $colors['header'] }} border-b {{ $colors['border'] }} px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 {{ $colors['iconBg'] }} rounded-lg flex items-center justify-center">
                                                <i class="{{ $module['icon'] }} text-lg {{ $colors['icon'] }}"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold text-text-primary leading-snug">{{ $module['name'] }}</h3>
                                                <p class="text-xs text-text-secondary leading-normal">{{ count($module['entities']) }} موجودیت</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <button type="button" onclick="toggleModulePermissions('{{ $module['id'] }}', true)"
                                                    class="text-xs text-text-secondary hover:text-primary transition-colors duration-200">انتخاب همه</button>
                                            <span class="text-border-medium">|</span>
                                            <button type="button" onclick="toggleModulePermissions('{{ $module['id'] }}', false)"
                                                    class="text-xs text-text-secondary hover:text-red-600 transition-colors duration-200">حذف انتخاب</button>
                                            <button type="button" onclick="toggleModule('{{ $module['id'] }}')"
                                                    class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-white/50 rounded-lg transition-all duration-200">
                                                <i class="fa-solid fa-chevron-down module-toggle-icon transition-transform duration-200" id="icon-{{ $module['id'] }}"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="module-content" id="content-{{ $module['id'] }}">
                                    <div class="overflow-x-auto">
                                        <table class="w-full">
                                            <thead>
                                                <tr class="{{ $colors['bg'] }} border-b {{ $colors['border'] }}">
                                                    <th class="px-6 py-3 text-right text-sm font-semibold text-text-primary leading-normal min-w-[180px]">
                                                        <div class="flex items-center gap-2">
                                                            <i class="fa-solid fa-cube text-text-muted"></i>
                                                            موجودیت
                                                        </div>
                                                    </th>
                                                    @foreach($standardPermissions as $permKey => $perm)
                                                        <th class="px-3 py-3 text-center text-xs font-medium {{ $colors['headerText'] }} leading-normal min-w-[90px]">
                                                            <div class="flex flex-col items-center gap-1">
                                                                <i class="{{ $perm['icon'] }} text-sm opacity-70"></i>
                                                                <span>{{ $perm['name'] }}</span>
                                                            </div>
                                                        </th>
                                                    @endforeach
                                                    <th class="px-3 py-3 text-center text-xs font-medium text-text-secondary leading-normal min-w-[70px]">
                                                        <span>عملیات</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($module['entities'] as $entity)
                                                    <tr class="border-b border-border-light last:border-0 hover:bg-bg-secondary/50 transition-colors duration-200">
                                                        <td class="px-6 py-4">
                                                            <span class="text-sm font-medium text-text-primary leading-normal">{{ $entity['name'] }}</span>
                                                        </td>
                                                        @foreach($standardPermissions as $permKey => $perm)
                                                            @php
                                                                $permissionName = $module['id'] . '.' . $entity['id'] . '.' . $permKey;
                                                                $isChecked = in_array($permissionName, $rolePermissions);
                                                            @endphp
                                                            <td class="px-3 py-4 text-center">
                                                                <label class="inline-flex items-center justify-center cursor-pointer">
                                                                    <input type="checkbox"
                                                                           name="permissions[]"
                                                                           value="{{ $permissionName }}"
                                                                           data-module="{{ $module['id'] }}"
                                                                           data-entity="{{ $module['id'] }}_{{ $entity['id'] }}"
                                                                           data-permission="{{ $permKey }}"
                                                                           class="w-4 h-4 {{ $colors['checkbox'] }} rounded border-border-medium cursor-pointer transition-all duration-200"
                                                                           {{ $isChecked ? 'checked' : '' }}>
                                                                </label>
                                                            </td>
                                                        @endforeach
                                                        <td class="px-3 py-4 text-center">
                                                            <div class="flex items-center justify-center gap-1">
                                                                <button type="button" onclick="toggleEntityPermissions('{{ $module['id'] }}_{{ $entity['id'] }}', true)"
                                                                        class="w-7 h-7 flex items-center justify-center text-text-muted hover:text-green-600 hover:bg-green-50 rounded transition-all duration-200"
                                                                        title="انتخاب همه">
                                                                    <i class="fa-solid fa-check-double text-xs"></i>
                                                                </button>
                                                                <button type="button" onclick="toggleEntityPermissions('{{ $module['id'] }}_{{ $entity['id'] }}', false)"
                                                                        class="w-7 h-7 flex items-center justify-center text-text-muted hover:text-red-600 hover:bg-red-50 rounded transition-all duration-200"
                                                                        title="حذف انتخاب">
                                                                    <i class="fa-solid fa-xmark text-xs"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
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

    @push('scripts')
    <script>
        function toggleModule(moduleId) {
            const content = document.getElementById('content-' + moduleId);
            const icon = document.getElementById('icon-' + moduleId);

            if (content.style.display === 'none') {
                content.style.display = 'block';
                icon.style.transform = 'rotate(0deg)';
            } else {
                content.style.display = 'none';
                icon.style.transform = 'rotate(180deg)';
            }
        }

        function toggleModulePermissions(moduleId, checked) {
            const checkboxes = document.querySelectorAll(`input[data-module="${moduleId}"]`);
            checkboxes.forEach(cb => cb.checked = checked);
        }

        function toggleEntityPermissions(entityId, checked) {
            const checkboxes = document.querySelectorAll(`input[data-entity="${entityId}"]`);
            checkboxes.forEach(cb => cb.checked = checked);
        }

        function selectAllPermissions() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name="permissions[]"]');
            checkboxes.forEach(cb => cb.checked = true);
        }

        function deselectAllPermissions() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name="permissions[]"]');
            checkboxes.forEach(cb => cb.checked = false);
        }
    </script>
    @endpush
</x-layouts.app>
