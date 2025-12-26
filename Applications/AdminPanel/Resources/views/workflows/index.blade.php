@php
    $title = $pageTitle ?? 'مدیریت فرایندها';
    $currentPage = 'bpms-workflows';
    $createLabel = 'ایجاد فرایند جدید';
    $actionButtons = [
        ['label' => $createLabel, 'url' => route('web.bpms.workflows.create'), 'icon' => 'fa-solid fa-plus', 'type' => 'primary'],
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
            'placeholder' => 'جستجوی فرایند...',
            'value' => request('search'),
        ],
        [
            'type' => 'tom-select-ajax',
            'name' => 'department_id',
            'label' => 'دپارتمان',
            'template' => 'departments',
            'url' => route('api.v1.admin.org.departments.index', ['per_page' => 100, 'field' => 'name', 'filter' => ['parent_id' => ''], 'includes' => 'children']),
            'selected' => request('department_id'),
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
        ]
    ];

    $resetUrl = route('web.bpms.workflows.index');

    $columns = [
        [
            'key' => 'name',
            'label' => 'فرایند',
            'component' => 'status-dot-text',
            'options' => [
                'status_key' => 'is_active',
                'label_key' => 'name',
                'title_key' => 'slug',
            ],
        ],
        [
            'key' => 'department',
            'label' => 'دپارتمان',
            'component' => 'department-badge',
            'options' => [
                'fallback_color' => 'green',
                'empty_label' => '-',
            ],
        ],
        [
            'key' => 'owner',
            'label' => 'مدیر',
            'component' => 'owner-inline',
            'options' => [
                'owner_key' => 'owner',
                'name_key' => 'full_name',
            ],
        ],
        [
            'key' => 'states',
            'label' => 'مراحل',
            'component' => 'workflow-states',
            'align' => 'center',
        ],
    ];

    $actions = [
        [
            'icon' => 'fa-pen',
            'type' => 'link',
            'tooltip' => 'ویرایش فرایند',
            'url' => function ($row) {
                return route('web.bpms.workflows.edit', $row['id']);
            },
        ],
        [
            'icon' => 'fa-trash',
            'type' => 'button',
            'variant' => 'danger',
            'tooltip' => 'حذف فرایند',
            'data_attrs' => [
                'data-ajax' => '',
                'data-confirm' => 'آیا از حذف این کارمند اطمینان دارید؟',
                'data-action' => function($row) {
                    return route('api.v1.admin.bpms.workflows.destroy', $row['id']);
                },
                'data-method' => 'DELETE',
                'data-on-success' => 'reload'
            ],
        ],
    ];

    $rowClass = function ($row) {
        return empty($row['is_active']) ? 'opacity-60' : null;
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

                <form method="GET" action="{{ route('web.bpms.workflows.index') }}" class="mb-6">
                    <x-panel::ui.filter-bar :filters="$filters" :resetUrl="$resetUrl"/>
                </form>

                <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-text-primary leading-snug">لیست فرایندها</h2>
                    </div>

                    <x-panel::ui.table.auto
                        :columns="$columns"
                        :data="$workflows"
                        :actions="$actions"
                        :row-class="$rowClass"
                        empty-icon="fa-diagram-project"
                        empty-message="فرایندی یافت نشد"
                    />

                    @if(!empty($pagination['links'] ?? null))
                        <div class="px-6 py-4 border-t border-border-light">
                            {{ $pagination['links'] }}
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</x-panel::layouts.app>
