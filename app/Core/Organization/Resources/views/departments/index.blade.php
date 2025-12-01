@php
    $title = $pageTitle ?? 'مدیریت دپارتمان‌ها';
    $currentPage = 'org-departments';
    $createLabel = 'افزودن دپارتمان جدید';
    $actionButtons = [
        ['label' => $createLabel, 'url' => route('web.org.departments.create'), 'icon' => 'fa-solid fa-plus', 'type' => 'primary'],
    ];

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'ساختار سازمانی', 'url' => route('web.org.dashboard')],
        ['label' => $pageTitle],
    ];

    $filters = [
        [
            'type' => 'text',
            'name' => 'search',
            'label' => 'جستجو',
            'placeholder' => 'نام یا کد دپارتمان',
            'value' => request('search'),
        ],
        [
            'type' => 'select',
            'name' => 'status',
            'label' => 'وضعیت',
            'options' => [
                '' => 'همه',
                'active' => 'فعال',
                'inactive' => 'غیرفعال',
            ],
            'selected' => request('status'),
        ],
    ];

    $resetUrl = route('web.org.departments.index');

    $totalDepartments = count($departments);
    $countActive = function ($items) use (&$countActive) {
        $count = 0;
        foreach ($items as $dept) {
            if (!empty($dept['is_active'])) {
                $count++;
            }
            if (!empty($dept['children'])) {
                $count += $countActive($dept['children']);
            }
        }
        return $count;
    };
    $activeDepartments = $countActive($departments);
    $sumEmployees = function ($items) use (&$sumEmployees) {
        $total = 0;
        foreach ($items as $dept) {
            $total += (int) ($dept['employees_count'] ?? 0);
            if (!empty($dept['children'])) {
                $total += $sumEmployees($dept['children']);
            }
        }
        return $total;
    };
    $totalEmployees = $sumEmployees($departments);
    $maxLevel = 3;
    $stats = [
        ['title' => 'کل دپارتمان‌ها', 'value' => $totalDepartments, 'icon' => 'fa-solid fa-building', 'color' => 'blue'],
        ['title' => 'دپارتمان‌های فعال', 'value' => $activeDepartments, 'icon' => 'fa-solid fa-check-circle', 'color' => 'green'],
        ['title' => 'کل کارمندان', 'value' => $totalEmployees, 'icon' => 'fa-solid fa-users', 'color' => 'purple'],
        ['title' => 'سطوح سازمانی', 'value' => $maxLevel, 'icon' => 'fa-solid fa-sitemap', 'color' => 'orange'],
    ];
@endphp

<x-layouts.app :title="$title">
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
                :title="$title"
                :breadcrumbs="$breadcrumbs"
                :actions="$actionButtons"
            />

            <div class="flex-1 p-6 lg:p-8">

                <x-dashboard.stats :items="$stats" cols="grid-cols-1 sm:grid-cols-2 lg:grid-cols-4"/>

                <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-text-primary leading-snug">ساختار سازمانی</h2>
                        <div class="flex items-center gap-2">
                            <button type="button" class="expand-all bg-bg-secondary text-text-secondary border border-border-medium px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-sm leading-normal">
                                <i class="fa-solid fa-expand ml-2"></i>
                                <span>باز کردن همه</span>
                            </button>
                            <button type="button" class="collapse-all bg-bg-secondary text-text-secondary border border-border-medium px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-sm leading-normal">
                                <i class="fa-solid fa-compress ml-2"></i>
                                <span>بستن همه</span>
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <x-ui.table.head>
                                <x-ui.table.th>نام دپارتمان</x-ui.table.th>
                                <x-ui.table.th>کد</x-ui.table.th>
                                <x-ui.table.th>مدیر</x-ui.table.th>
                                <x-ui.table.th>تعداد کارمندان</x-ui.table.th>
                                <x-ui.table.th>وضعیت</x-ui.table.th>
                                <x-ui.table.th align="center">عملیات</x-ui.table.th>
                            </x-ui.table.head>

                            <x-ui.table.body>
                                @forelse($departments as $dept)
                                    @include('Organization::departments.partials.department-row', ['dept' => $dept, 'level' => 0])
                                @empty
                                    <x-ui.table.empty :colspan="6" icon="fa-building" message="دپارتمانی یافت نشد" />
                                @endforelse
                            </x-ui.table.body>
                        </table>
                    </div>
                </div>

            </div>

        </main>

    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.toggle-dept');

            function toggleChildren(parentId, show) {
                const children = document.querySelectorAll(`.child-of-${parentId}`);
                children.forEach(child => {
                    if (show) {
                        child.style.display = '';
                    } else {
                        child.style.display = 'none';
                        const childId = child.dataset.deptId;
                        if (child.dataset.hasChildren === '1') {
                            toggleChildren(childId, false);
                            const childBtn = child.querySelector('.toggle-dept');
                            if (childBtn) {
                                const childIcon = childBtn.querySelector('i');
                                childIcon.style.transform = 'rotate(0deg)';
                            }
                        }
                    }
                });
            }

            toggleButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const parentId = this.dataset.parentId;
                    const icon = this.querySelector('i');
                    const isOpen = icon.style.transform === 'rotate(-180deg)';

                    if (isOpen) {
                        icon.style.transform = 'rotate(0deg)';
                        toggleChildren(parentId, false);
                    } else {
                        icon.style.transform = 'rotate(-180deg)';
                        toggleChildren(parentId, true);
                    }
                });
            });

            document.querySelector('.expand-all')?.addEventListener('click', function() {
                document.querySelectorAll('.department-row').forEach(row => {
                    row.style.display = '';
                });
                toggleButtons.forEach(btn => {
                    const icon = btn.querySelector('i');
                    icon.style.transform = 'rotate(-180deg)';
                });
            });

            document.querySelector('.collapse-all')?.addEventListener('click', function() {
                document.querySelectorAll('.department-row[data-parent-id]').forEach(row => {
                    row.style.display = 'none';
                });
                toggleButtons.forEach(btn => {
                    const icon = btn.querySelector('i');
                    icon.style.transform = 'rotate(0deg)';
                });
            });
        });
    </script>
    @endpush
</x-layouts.app>
