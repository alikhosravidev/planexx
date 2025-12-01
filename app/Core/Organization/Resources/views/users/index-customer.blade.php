@php
    $title = $pageTitle ?? 'مدیریت مشتریان';
    $currentPage = 'org-customers';
    $createUrl = route('web.org.users.index') . '?type=customer';
    $createLabel = 'افزودن مشتری جدید';
    $actionButtons = [
        ['label' => $createLabel, 'url' => $createUrl, 'icon' => 'fa-solid fa-plus', 'type' => 'primary'],
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
            'value' => request('search')
        ],
        [
            'type' => 'select',
            'name' => 'status',
            'label' => 'وضعیت',
            'options' => [
                '' => 'همه',
                'active' => 'فعال',
                'inactive' => 'غیرفعال'
            ],
            'selected' => request('status')
        ]
    ];

    $resetUrl = route('web.org.users.index') . '?type=customer';
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
            <x-dashboard.module-header
                :page-title="$title"
                :breadcrumbs="$breadcrumbs"
                :action-buttons="$actionButtons"
            />

            <div class="flex-1 p-6 lg:p-8">

                <form method="GET" action="{{ route('web.org.users.index') }}" class="mb-6">
                    <input type="hidden" name="type" value="customer">
                    <x-ui.filter-bar :filters="$filters" :resetUrl="$resetUrl" />
                </form>

                @php
                    $columns = [
                        [
                            'key' => 'full_name',
                            'label' => 'مشتری',
                            'component' => 'user',
                            'options' => [
                                'image_key' => 'image_url',
                            ],
                        ],
                        [
                            'key' => 'mobile',
                            'label' => 'شماره موبایل',
                            'component' => 'text',
                            'align' => 'left',
                            'options' => ['dir' => 'ltr'],
                        ],
                        [
                            'key' => 'email',
                            'label' => 'ایمیل',
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
                            'icon' => 'fa-eye',
                            'type' => 'link',
                            'tooltip' => 'مشاهده جزئیات',
                            'route' => '#',
                        ],
                        [
                            'icon' => 'fa-pen',
                            'type' => 'link',
                            'tooltip' => 'ویرایش',
                            'route' => '#',
                        ],
                        [
                            'icon' => 'fa-trash',
                            'type' => 'button',
                            'variant' => 'danger',
                            'tooltip' => 'حذف',
                            'data_attrs' => [
                                'data-action' => 'delete',
                                'data-confirm' => 'آیا از حذف این مشتری اطمینان دارید؟',
                                'data-url' => function($row) {
                                    return route('api.v1.admin.org.users.destroy', ['user' => $row['id']]);
                                },
                                'data-method' => 'DELETE',
                                'data-redirect' => 'reload'
                            ],
                        ],
                    ];
                @endphp

                <div class="bg-white border border-border-light rounded-2xl overflow-hidden">
                    <x-ui.table.auto
                        :columns="$columns"
                        :data="$users"
                        :actions="$actions"
                        empty-icon="fa-users"
                        empty-message="مشتری یافت نشد"
                    />

                    @if(!empty($users) && !empty($pagination))
                        @php
                            $currentPage = $pagination['current_page'] ?? 1;
                            $lastPage = $pagination['last_page'] ?? 1;
                        @endphp
                        <x-ui.pagination
                            :from="$pagination['from'] ?? 1"
                            :to="$pagination['to'] ?? count($users)"
                            :total="$pagination['total'] ?? count($users)"
                            label="مشتری"
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
</x-layouts.app>
