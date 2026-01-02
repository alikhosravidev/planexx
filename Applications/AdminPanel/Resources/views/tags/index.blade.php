@php
    $title = 'مدیریت برچسب‌ها';
    $currentPage = 'app.tags';

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => $title],
    ];

    $actionButtons = [
        ['label' => 'افزودن برچسب جدید', 'url' => route('web.app.tags.create'), 'icon' => 'fa-solid fa-plus', 'type' => 'primary'],
    ];

    $columns = [
        [
            'key' => 'name',
            'label' => 'نام برچسب',
            'component' => 'badge',
            'options' => [
                'icon' => function($row) { return $row['icon'] ?? 'fa-tag'; },
                'color' => function($row) { return $row['color'] ?? 'blue-500'; },
                'size' => 'md',
            ],
        ],
        [
            'key' => 'description.full',
            'label' => 'توضیحات',
            'component' => 'text',
            'options' => [
                'default' => '-',
            ],
        ],
        [
            'key' => 'usage_count',
            'label' => 'تعداد استفاده',
            'component' => 'badge',
            'options' => [
                'variant' => 'info',
                'size' => 'sm',
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
            'icon' => 'fa-pen',
            'type' => 'link',
            'tooltip' => 'ویرایش',
            'url' => function($row) {
                return route('web.app.tags.edit', ['tag' => $row['id']]);
            },
        ],
        [
            'icon' => 'fa-trash',
            'type' => 'button',
            'variant' => 'danger',
            'tooltip' => 'حذف',
            'data_attrs' => [
                'data-ajax' => '',
                'data-confirm' => 'آیا از حذف این برچسب اطمینان دارید؟',
                'data-action' => function($row) {
                    return route('api.v1.admin.tags.destroy', ['tag' => $row['id']]);
                },
                'data-method' => 'DELETE',
                'data-on-success' => 'reload'
            ],
        ],
    ];

    $filters = [
        [
            'type' => 'text',
            'name' => 'search',
            'label' => 'جستجو',
            'placeholder' => 'جستجو در نام، توضیحات و اسلاگ ...',
            'value' => request('search')
        ],
    ];
@endphp

<x-panel::layouts.app :title="$title">
    <div class="flex min-h-screen">
        <x-panel::dashboard.sidebar
            name="org.sidebar"
            :current-page="$currentPage"
            header-variant="back"
            module-title="مدیریت برچسب‌ها"
            module-icon="fa-solid fa-tags"
        />

        <main class="flex-1 flex flex-col min-w-0">
            <x-panel::dashboard.header
                :title="$title"
                :breadcrumbs="$breadcrumbs"
                :actions="$actionButtons"
            />

            <div class="flex-1 p-6 lg:p-8">

                <x-panel::dashboard.stats :items="$stats" cols="grid-cols-1 grid-cols-2 lg:grid-cols-4"/>

                <form method="GET" action="{{ route('web.app.tags.index') }}" class="mb-6">
                    <x-panel::ui.filter-bar :filters="$filters" :resetUrl="route('web.app.tags.index')" />
                </form>

                <div class="bg-white border border-border-light rounded-2xl overflow-hidden">
                    <x-panel::ui.table.auto
                        :columns="$columns"
                        :data="$tags"
                        :actions="$actions"
                        empty-icon="fa-tags"
                        empty-message="هیچ برچسبی یافت نشد"
                    />

                    @if(!empty($tags) && !empty($pagination))
                        @php
                            $currentPage = $pagination['current_page'] ?? 1;
                            $lastPage = $pagination['last_page'] ?? 1;
                        @endphp
                        <x-panel::ui.pagination
                            :from="$pagination['from'] ?? 1"
                            :to="$pagination['to'] ?? count($tags)"
                            :total="$pagination['total'] ?? count($tags)"
                            label="برچسب"
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
