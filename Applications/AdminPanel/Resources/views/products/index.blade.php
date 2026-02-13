@php
    $title = $pageTitle ?? 'مدیریت محصولات';
    $currentPage = 'products-list';

    $actionButtons = [
        ['label' => 'افزودن محصول جدید', 'url' => route('web.product.products.create'), 'icon' => 'fa-solid fa-plus', 'type' => 'primary'],
    ];

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'محصولات و لیست‌ها', 'url' => route('web.product.products.index')],
        ['label' => 'محصولات'],
    ];

    $filters = [
        [
            'type' => 'text',
            'name' => 'search',
            'label' => 'جستجو',
            'placeholder' => 'نام محصول، SKU یا دسته‌بندی',
            'value' => request('search'),
        ],
        [
            'type' => 'tom-select-ajax',
            'name' => 'category_id',
            'label' => 'دسته‌بندی',
            'template' => 'keyValList',
            'url' => route('api.v1.admin.product.categories.keyValList', ['per_page' => 100, 'field' => 'name']),
            'selected' => request('category_id'),
        ],
        [
            'type' => 'select',
            'name' => 'status',
            'label' => 'وضعیت',
            'options' => [
                '' => 'همه',
                'active' => 'فعال',
                'out_of_stock' => 'ناموجود',
                'draft' => 'پیش‌نویس',
            ],
            'selected' => request('status'),
        ],
    ];

    $resetUrl = route('web.product.products.index');

    $columns = [
        [
            'key' => 'title',
            'label' => 'محصول',
            'component' => 'icon-text',
            'options' => [
                'icon' => 'fa-solid fa-box',
                'icon_bg' => 'bg-primary/10',
                'icon_color' => 'text-primary',
            ],
            'width' => '280px',
        ],
        [
            'key' => 'sku',
            'label' => 'کد محصول',
            'class' => 'font-mono',
        ],
        [
            'key' => 'categories.0.name',
            'label' => 'دسته‌بندی',
            'component' => 'badge',
            'options' => [
                'class' => 'bg-slate-100 text-slate-600',
            ],
        ],
        [
            'key' => 'price.main',
            'label' => 'قیمت (ریال)',
            'class' => 'font-medium',
        ],
        [
            'key' => 'status.label',
            'label' => 'وضعیت',
            'component' => 'badge',
            'options' => [
                'variant' => fn($row) => $row['status']['variant'],
                'size' => 'sm',
            ],
        ],
        [
            'key' => 'created_at.human.default',
            'label' => 'تاریخ ایجاد',
            'class' => 'text-sm text-text-secondary',
        ],
    ];

    $actions = [
        [
            'icon' => 'fa-eye',
            'type' => 'link',
            'tooltip' => 'مشاهده',
            'url' => function ($row) {
                return route('web.product.products.show', $row['id']);
            },
        ],
        [
            'icon' => 'fa-pen',
            'type' => 'link',
            'tooltip' => 'ویرایش',
            'url' => function ($row) {
                return route('web.product.products.edit', $row['id']);
            },
        ],
        [
            'icon' => 'fa-trash',
            'type' => 'button',
            'variant' => 'danger',
            'tooltip' => 'حذف',
            'data_attrs' => [
                'data-ajax' => '',
                'data-confirm' => 'آیا از حذف این محصول اطمینان دارید؟ این عملیات قابل بازگشت نیست.',
                'data-action' => function($row) {
                    return route('api.v1.admin.product.products.destroy', $row['id']);
                },
                'data-method' => 'DELETE',
                'data-on-success' => 'reload',
            ],
        ],
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

                <form method="GET" action="{{ route('web.product.products.index') }}" class="mb-6">
                    <x-panel::ui.filter-bar :filters="$filters" :resetUrl="$resetUrl" size="70"/>
                </form>

                <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-text-primary leading-snug">لیست محصولات</h2>
                        <span class="text-sm text-text-muted">{{ $pagination['total'] ?? count($products) }} محصول</span>
                    </div>

                    <x-panel::ui.table.auto
                        :columns="$columns"
                        :data="$products"
                        :actions="$actions"
                        :hoverable="true"
                        empty-icon="fa-box"
                        empty-message="محصولی یافت نشد"
                        empty-description="با تغییر فیلترها یا جستجو، محصول مورد نظر را پیدا کنید"
                        empty-action-text="افزودن محصول جدید"
                    />

                    @if(!empty($products) && !empty($pagination))
                        @php
                            $currentPageNum = $pagination['current_page'] ?? 1;
                            $lastPage = $pagination['last_page'] ?? 1;
                        @endphp
                        <x-panel::ui.pagination
                            :from="$pagination['from'] ?? 1"
                            :to="$pagination['to'] ?? count($products)"
                            :total="$pagination['total'] ?? count($products)"
                            label="محصول"
                            :current="$currentPageNum"
                            :prevUrl="request()->fullUrlWithQuery(['page' => $currentPageNum - 1])"
                            :nextUrl="request()->fullUrlWithQuery(['page' => $currentPageNum + 1])"
                            :hasPrev="$currentPageNum > 1"
                            :hasNext="$currentPageNum < $lastPage"
                        />
                    @endif
                </div>

            </div>
        </main>
    </div>

    @vite('Applications/AdminPanel/Resources/js/pages/products.js')
</x-panel::layouts.app>
