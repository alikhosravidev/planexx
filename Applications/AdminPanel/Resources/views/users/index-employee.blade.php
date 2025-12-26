@php
    $title = $pageTitle ?? 'مدیریت کارکنان';
    $createLabel = 'افزودن کارمند جدید';
    $actionButtons = [
        [
            'label' => $createLabel,
            'url' => route('web.org.users.create', ['user_type' => 'employee']),
            'icon' => 'fa-solid fa-plus',
            'type' => 'primary',
        ],
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
            'placeholder' => 'نام، موبایل یا ایمیل',
            'value' => request('search'),
        ],
    ];

    $filters[] = [
        'type' => 'tom-select-ajax',
        'name' => 'department_id',
        'label' => 'دپارتمان',
        'template' => 'departments',
        'url' => route('api.v1.admin.org.departments.index', ['per_page' => 100, 'field' => 'name', 'filter' => ['parent_id' => ''], 'includes' => 'children']),
        'selected' => request('department_id'),
    ];

    $filters[] = [
        'type' => 'select',
        'name' => 'status',
        'label' => 'وضعیت',
        'options' => [
            '' => 'همه',
            'active' => 'فعال',
            'inactive' => 'غیرفعال',
        ],
        'selected' => request('status'),
    ];


    $columns = [
        [
            'key' => 'full_name',
            'label' => 'کارمند',
            'component' => 'user',
            'options' => [
                'image_key' => 'avatar.file_url',
            ],
        ],
        [
            'key' => 'mobile',
            'label' => 'شماره موبایل',
            'component' => 'text',
        ],
        [
            'key' => 'email',
            'label' => 'ایمیل',
            'component' => 'text',
        ],
        [
            'key' => 'departments.0.name',
            'label' => 'دپارتمان',
            'component' => 'text',
        ],
        [
            'key' => 'primaryRoles.0.title',
            'label' => 'نقش کاربری',
            'component' => 'text',
        ],
        [
            'key' => 'is_active',
            'label' => 'وضعیت',
            'component' => 'status',
        ],
    ];

    $actions = [
        [
            'icon' => 'fa-user-shield',
            'type' => 'button',
            'variant' => 'warning',
            'tooltip' => 'تنظیم سطح دسترسی',
            'data_attrs' => [
                'data-modal-open' => 'accessModal',
                'data-modal-data' => function($row) {
                    return json_encode([
                        'userId' => $row['id'],
                        'userName' => $row['full_name']
                    ]);
                }
            ],
        ],
        [
            'icon' => 'fa-eye',
            'type' => 'link',
            'tooltip' => 'مشاهده جزئیات',
            'url' => function($row) {
                return isset($row['id']) ? route('web.org.users.show', ['user' => $row['id']]) : '#';
            },
        ],
        [
            'icon' => 'fa-pen',
            'type' => 'link',
            'tooltip' => 'ویرایش',
            'url' => function($row) {
                return isset($row['id']) ? route('web.org.users.edit', ['user' => $row['id']]) : '#';
            },
        ],
        [
            'icon' => 'fa-trash',
            'type' => 'button',
            'variant' => 'danger',
            'tooltip' => 'حذف',
            'data_attrs' => [
                'data-ajax' => '',
                'data-confirm' => 'آیا از حذف این کارمند اطمینان دارید؟',
                'data-action' => function($row) {
                    return route('api.v1.admin.org.users.destroy', ['user' => $row['id']]);
                },
                'data-method' => 'DELETE',
                'data-on-success' => 'reload'
            ],
        ],
    ];

    $resetUrl = route('web.org.users.index') . '?user_type=employee';
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

                <form method="GET" action="{{ route('web.org.users.index') }}" class="mb-6">
                    <input type="hidden" name="user_type" value="{{ $userType->name }}">
                    <x-panel::ui.filter-bar :filters="$filters" :resetUrl="$resetUrl"/>
                </form>

                <div class="bg-white border border-border-light rounded-2xl overflow-hidden">
                    <x-panel::ui.table.auto
                            :columns="$columns"
                            :data="$users"
                            :actions="$actions"
                            empty-icon="fa-user-tie"
                            empty-message="کارمندی یافت نشد"
                    />

                    @if(!empty($users) && !empty($pagination))
                        @php
                            $currentPage = $pagination['current_page'] ?? 1;
                            $lastPage = $pagination['last_page'] ?? 1;
                        @endphp
                        <x-panel::ui.pagination
                                :from="$pagination['from'] ?? 1"
                                :to="$pagination['to'] ?? count($users)"
                                :total="$pagination['total'] ?? count($users)"
                                label="کارمند"
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

    <x-panel::access-modal :roles="$roles"/>
</x-panel::layouts.app>
