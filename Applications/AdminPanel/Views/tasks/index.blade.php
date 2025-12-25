@php
    $title = $pageTitle ?? 'کارها و وظایف';
    $currentPage = 'bpms-tasks';
    $createLabel = 'افزودن کار جدید';
    $actionButtons = [
        ['label' => $createLabel, 'url' => '#', 'icon' => 'fa-solid fa-plus', 'type' => 'primary', 'data_attrs' => ['data-modal-open' => 'taskModal']],
    ];

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'مدیریت وظایف', 'url' => route('web.bpms.dashboard')],
        ['label' => $pageTitle],
    ];

    $filters = [
        [
            'type' => 'text',
            'name' => 'search',
            'label' => 'جستجو',
            'placeholder' => 'جستجوی کار...',
            'value' => request('search'),
        ],
        [
            'type' => 'tom-select-ajax',
            'name' => 'workflow_id',
            'label' => 'فرایند',
            'template' => 'keyValList',
            'url' => route('api.v1.admin.bpms.workflows.keyValList', ['per_page' => 100, 'field' => 'name']),
            'selected' => request('workflow_id'),
            'placeholder' => 'همه فرایندها',
        ],
        [
            'type' => 'tom-select-ajax',
            'name' => 'assignee_id',
            'label' => 'مسئول',
            'template' => 'keyValList',
            'url' => route('api.v1.admin.org.users.keyValList', ['per_page' => 100, 'field' => 'full_name']),
            'selected' => request('assignee_id'),
            'placeholder' => 'همه افراد',
        ],
        [
            'type' => 'select',
            'name' => 'priority',
            'label' => 'اولویت',
            'options' => [
                '' => 'همه',
                '3' => 'فوری',
                '2' => 'بالا',
                '1' => 'متوسط',
                '0' => 'کم',
            ],
            'selected' => request('priority'),
        ],
    ];

    $resetUrl = route('web.bpms.tasks.index');

    // TODO: load stats using registry service.
    $statItems = [
        [
            'title' => 'کل کارها',
            'value' => $stats['total'] ?? 0,
            'icon' => 'fa-solid fa-list-check',
            'color' => 'blue',
        ],
        [
            'title' => 'فوری',
            'value' => $stats['urgent'] ?? 0,
            'icon' => 'fa-solid fa-fire',
            'color' => 'orange',
        ],
        [
            'title' => 'ددلاین امروز',
            'value' => $stats['today'] ?? 0,
            'icon' => 'fa-solid fa-clock',
            'color' => 'purple',
        ],
        [
            'title' => 'عقب‌افتاده',
            'value' => $stats['overdue'] ?? 0,
            'icon' => 'fa-solid fa-triangle-exclamation',
            'color' => 'green',
        ],
    ];

    $columns = [
        [
            'key' => 'title',
            'label' => 'عنوان کار',
            'component' => 'task-title',
            'width' => '300px',
        ],
        [
            'key' => 'current_state',
            'label' => 'وضعیت و پیشرفت',
            'component' => 'task-progress',
            'align' => 'center',
        ],
        [
            'key' => 'assignee.full_name',
            'label' => 'مسئول',
            'component' => 'user',
            'options' => [
                'image_key' => 'assignee.avatar.file_url',
            ],
        ],
        [
            'key' => 'due_date',
            'label' => 'ددلاین',
            'component' => 'task-deadline',
            'align' => 'center',
        ],
    ];

    $actions = [
        [
            'icon' => 'fa-eye',
            'type' => 'link',
            'tooltip' => 'مشاهده جزئیات',
            'url' => function ($row) {
                return route('web.bpms.tasks.show', $row['id']);
            },
        ],
        [
            'icon' => 'fa-pen',
            'type' => 'button',
            'tooltip' => 'ویرایش کار',
            'x-click' => function($row) {
                $taskJson = json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
                return "openEditTaskModal({$taskJson})";
            },
        ],
        [
            'icon' => 'fa-trash',
            'type' => 'button',
            'variant' => 'danger',
            'tooltip' => 'حذف کار',
            'data_attrs' => [
                'data-ajax' => '',
                'data-confirm' => 'آیا از حذف این کار اطمینان دارید؟',
                'data-action' => function($row) {
                    return route('api.v1.admin.bpms.tasks.destroy', $row['id']);
                },
                'data-method' => 'DELETE',
                'data-on-success' => 'reload'
            ],
        ],
    ];

    $rowClass = function ($row) {
        $remainingDays = $row['remaining_days'] ?? null;
        if ($remainingDays !== null && $remainingDays < 0) {
            return 'bg-red-50/50';
        }
        return null;
    };
@endphp

<x-panel::layouts.app :title="$title">
    <div class="flex min-h-screen">
        <x-panel::dashboard.sidebar
            name="bpms.sidebar"
            :current-page="$currentPage"
            header-variant="back"
            module-title="مدیریت وظایف"
            module-icon="fa-solid fa-diagram-project"
            color="indigo"
        />

        <main class="flex-1 flex flex-col min-w-0">
            <x-panel::dashboard.header
                :title="$title"
                :breadcrumbs="$breadcrumbs"
                :actions="$actionButtons"
            />

            <div class="flex-1 p-6 lg:p-8">

                <form method="GET" action="{{ route('web.bpms.tasks.index') }}" class="mb-6">
                    <x-panel::ui.filter-bar :filters="$filters" :resetUrl="$resetUrl" size="70"/>
                </form>

                <x-panel::dashboard.stats :items="$statItems" />

                <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-text-primary leading-snug">لیست کارها</h2>
                        <span class="text-sm text-text-muted">{{ count($tasks) }} کار</span>
                    </div>

                    <x-panel::ui.table.auto
                        :columns="$columns"
                        :data="$tasks"
                        :actions="$actions"
                        :row-class="$rowClass"
                        :hoverable="true"
                        empty-icon="fa-tasks"
                        empty-message="کاری یافت نشد"
                        empty-description="با تغییر فیلترها یا جستجو، کار مورد نظر را پیدا کنید"
                        :empty-action-text="$createLabel"
                        :empty-action-url="route('web.bpms.tasks.create')"
                    />

                    @if(!empty($tasks) && !empty($pagination))
                        @php
                            $currentPage = $pagination['current_page'] ?? 1;
                            $lastPage = $pagination['last_page'] ?? 1;
                        @endphp
                        <x-panel::ui.pagination
                            :from="$pagination['from'] ?? 1"
                            :to="$pagination['to'] ?? count($tasks)"
                            :total="$pagination['total'] ?? count($tasks)"
                            label="کار"
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

    {{-- Create Task Modal --}}
    <x-panel::bpms.create-task-modal />

    @vite('resources/js/pages/bpms-tasks.js')
</x-panel::layouts.app>
