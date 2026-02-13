@php
    $title = $pageTitle ?? 'دسته‌بندی محصولات';
    $currentPage = 'product-categories';

    $actionButtons = [
        ['label' => 'افزودن دسته‌بندی', 'url' => '#', 'icon' => 'fa-solid fa-plus', 'type' => 'primary', 'data_attrs' => ['data-modal-open' => 'categoryModal']],
    ];

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'محصولات و لیست‌ها', 'url' => route('web.product.products.index')],
        ['label' => 'دسته‌بندی محصولات'],
    ];

    // Filters
    $filters = [
        [
            'type' => 'text',
            'name' => 'search',
            'label' => 'جستجو',
            'placeholder' => 'جستجو در نام، نامک یا توضیحات...',
            'value' => request('search'),
        ],
        [
            'type' => 'select',
            'name' => 'parent_filter',
            'label' => 'نوع دسته‌بندی',
            'options' => [
                '' => 'همه',
                'root' => 'فقط دسته‌های اصلی',
                'sub' => 'فقط زیردسته‌ها',
            ],
            'selected' => request('parent_filter'),
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

    $columns = [
        [
            'key' => 'name',
            'label' => 'دسته‌بندی',
            'component' => 'icon-text',
            'options' => [
                'icon' => function($row) { return $row['icon'] ?? 'fa-solid fa-folder'; },
                'icon_bg' => 'bg-slate-50',
                'icon_color' => 'text-slate-600',
                'description' => function($row) { return $row['description']['full'] ?? null; },
                'description_class' => 'text-xs text-text-muted mt-0.5',
            ],
            'width' => '280px',
        ],
        [
            'key' => 'slug',
            'label' => 'نامک',
            'class' => 'font-mono text-sm',
            'options' => [
                'dir' => 'ltr',
            ]
        ],
        [
            'key' => 'parent.name',
            'label' => 'والد',
            'component' => 'badge',
            'options' => [
                'icon' => 'fa-solid fa-folder',
                'class' => 'bg-blue-50 text-blue-700',
                'default' => '—',
                'variant' => 'info',
            ],
        ],
        [
            'key' => 'products_count',
            'label' => 'محصولات',
            'component' => 'text',
            'options' => [
                'default' => '0',
            ],
        ],
        [
            'key' => 'children_count',
            'label' => 'زیردسته‌ها',
            'component' => 'text',
            'options' => [
                'default' => '0',
            ],
        ],
        [
            'key' => 'sort_order',
            'label' => 'ترتیب',
            'class' => 'text-sm text-text-muted',
        ],
        [
            'key' => 'is_active',
            'label' => 'وضعیت',
            'component' => 'status',
        ],
    ];

    // Actions
    $actions = [
        [
            'icon' => 'fa-pen',
            'type' => 'button',
            'tooltip' => 'ویرایش',
            'data_attrs' => [
                'onclick' => function($row) {
                    $catJson = json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
                    return "openEditCategoryModal({$catJson})";
                },
            ],
        ],
        [
            'icon' => 'fa-trash',
            'type' => 'button',
            'variant' => 'danger',
            'tooltip' => 'حذف',
            'data_attrs' => [
                'data-ajax' => '',
                'data-confirm' => 'آیا از حذف این دسته‌بندی اطمینان دارید؟ محصولات مرتبط بدون دسته‌بندی خواهند شد.',
                'data-action' => function($row) {
                    return route('api.v1.admin.product.categories.destroy', $row['id']);
                },
                'data-method' => 'DELETE',
                'data-on-success' => 'reload',
            ],
        ],
    ];

    $resetUrl = route('web.product.categories.index');

    // Category icons for the modal
    $categoryIcons = [
        'fa-solid fa-microchip', 'fa-solid fa-laptop', 'fa-solid fa-desktop', 'fa-solid fa-mobile-screen',
        'fa-solid fa-tv', 'fa-solid fa-headphones', 'fa-solid fa-camera', 'fa-solid fa-print',
        'fa-solid fa-utensils', 'fa-solid fa-mug-hot', 'fa-solid fa-wheat-awn', 'fa-solid fa-apple-whole',
        'fa-solid fa-pills', 'fa-solid fa-pump-soap', 'fa-solid fa-spray-can-sparkles', 'fa-solid fa-heart-pulse',
        'fa-solid fa-industry', 'fa-solid fa-gears', 'fa-solid fa-wrench', 'fa-solid fa-hammer',
        'fa-solid fa-briefcase', 'fa-solid fa-handshake', 'fa-solid fa-headset', 'fa-solid fa-truck',
        'fa-solid fa-box', 'fa-solid fa-boxes-stacked', 'fa-solid fa-tag', 'fa-solid fa-tags',
        'fa-solid fa-car', 'fa-solid fa-shirt', 'fa-solid fa-gem', 'fa-solid fa-couch',
        'fa-solid fa-book', 'fa-solid fa-palette', 'fa-solid fa-futbol', 'fa-solid fa-baby',
        'fa-solid fa-leaf', 'fa-solid fa-bolt', 'fa-solid fa-shield-halved', 'fa-solid fa-flask',
    ];
@endphp

<x-panel::layouts.app :title="$title">
    <div class="flex min-h-screen">
        <x-panel::dashboard.sidebar
            name="product.sidebar"
            :current-page="$currentPage"
            header-variant="back"
            module-title="محصولات و لیست‌ها"
            module-icon="fa-solid fa-boxes-stacked"
            color="blue"
        />

        <main class="flex-1 flex flex-col min-w-0">
            <x-panel::dashboard.header
                :title="$title"
                :breadcrumbs="$breadcrumbs"
                :actions="$actionButtons"
            />

            <div class="flex-1 p-6 lg:p-8">

                {{-- آمار دسته‌بندی‌ها --}}
                <x-panel::dashboard.stats :items="$stats" cols="grid-cols-1 sm:grid-cols-2 lg:grid-cols-4"/>

                {{-- فیلترها --}}
                <form method="GET" action="{{ route('web.product.categories.index') }}" class="mb-6">
                    <x-panel::ui.filter-bar :filters="$filters" :resetUrl="$resetUrl" />
                </form>

                {{-- جدول دسته‌بندی‌ها --}}
                <div class="bg-white border border-border-light rounded-2xl overflow-hidden">
                    <x-panel::ui.table.auto
                        :columns="$columns"
                        :data="$categories"
                        :actions="$actions"
                        empty-icon="fa-folder-tree"
                        empty-message="دسته‌بندی‌ای یافت نشد"
                    />

                    @if(!empty($categories) && !empty($pagination))
                        @php
                            $currentPage = $pagination['current_page'] ?? 1;
                            $lastPage = $pagination['last_page'] ?? 1;
                        @endphp
                        <x-panel::ui.pagination
                            :from="$pagination['from'] ?? 1"
                            :to="$pagination['to'] ?? count($categories)"
                            :total="$pagination['total'] ?? count($categories)"
                            label="دسته‌بندی"
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

    {{-- مودال ایجاد/ویرایش دسته‌بندی --}}
    @include('panel::products.modals.category-modal', [
        'categoryIcons' => $categoryIcons,
    ])

    @vite('Applications/AdminPanel/Resources/js/pages/product-categories.js')
</x-panel::layouts.app>
