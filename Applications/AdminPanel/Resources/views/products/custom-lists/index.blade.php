@php
    $title = $pageTitle ?? 'مدیریت لیست‌ها';
    $currentPage = 'lists-index';

    $actionButtons = [
        ['label' => 'ایجاد لیست جدید', 'url' => '#', 'icon' => 'fa-solid fa-plus', 'type' => 'primary', 'data_attrs' => ['data-modal-open' => 'listModal']],
    ];

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'محصولات و لیست‌ها', 'url' => route('web.product.products.index')],
        ['label' => 'لیست‌ها'],
    ];

    $cardColors = [
        'blue-500'   => ['bg' => 'bg-blue-50', 'icon' => 'text-blue-600', 'border' => 'hover:border-blue-200'],
        'green-500'  => ['bg' => 'bg-green-50', 'icon' => 'text-green-600', 'border' => 'hover:border-green-200'],
        'purple-500' => ['bg' => 'bg-purple-50', 'icon' => 'text-purple-600', 'border' => 'hover:border-purple-200'],
        'orange-500' => ['bg' => 'bg-orange-50', 'icon' => 'text-orange-600', 'border' => 'hover:border-orange-200'],
        'teal-500'   => ['bg' => 'bg-teal-50', 'icon' => 'text-teal-600', 'border' => 'hover:border-teal-200'],
        'red-500'    => ['bg' => 'bg-red-50', 'icon' => 'text-red-600', 'border' => 'hover:border-red-200'],
        'pink-500'   => ['bg' => 'bg-pink-50', 'icon' => 'text-pink-600', 'border' => 'hover:border-pink-200'],
        'indigo-500' => ['bg' => 'bg-indigo-50', 'icon' => 'text-indigo-600', 'border' => 'hover:border-indigo-200'],
        'amber-500'  => ['bg' => 'bg-amber-50', 'icon' => 'text-amber-600', 'border' => 'hover:border-amber-200'],
        'cyan-500'   => ['bg' => 'bg-cyan-50', 'icon' => 'text-cyan-600', 'border' => 'hover:border-cyan-200'],
        'lime-500'   => ['bg' => 'bg-lime-50', 'icon' => 'text-lime-600', 'border' => 'hover:border-lime-200'],
        'rose-500'   => ['bg' => 'bg-rose-50', 'icon' => 'text-rose-600', 'border' => 'hover:border-rose-200'],
    ];

    // List icons for modal
    $suggestedIcons = [
        'fa-solid fa-truck', 'fa-solid fa-warehouse', 'fa-solid fa-industry', 'fa-solid fa-building',
        'fa-solid fa-tools', 'fa-solid fa-wrench', 'fa-solid fa-cogs', 'fa-solid fa-desktop',
        'fa-solid fa-laptop', 'fa-solid fa-server', 'fa-solid fa-hard-drive', 'fa-solid fa-print',
        'fa-solid fa-chair', 'fa-solid fa-couch', 'fa-solid fa-car', 'fa-solid fa-motorcycle',
        'fa-solid fa-dolly', 'fa-solid fa-pallet', 'fa-solid fa-box-open', 'fa-solid fa-boxes-stacked',
        'fa-solid fa-flask', 'fa-solid fa-vial', 'fa-solid fa-microscope', 'fa-solid fa-stethoscope',
        'fa-solid fa-pills', 'fa-solid fa-syringe', 'fa-solid fa-shield-halved', 'fa-solid fa-fire-extinguisher',
        'fa-solid fa-helmet-safety', 'fa-solid fa-vest', 'fa-solid fa-utensils', 'fa-solid fa-mug-hot',
        'fa-solid fa-blender', 'fa-solid fa-kitchen-set', 'fa-solid fa-broom', 'fa-solid fa-spray-can-sparkles',
        'fa-solid fa-plug', 'fa-solid fa-bolt', 'fa-solid fa-battery-full', 'fa-solid fa-solar-panel',
        'fa-solid fa-fan', 'fa-solid fa-temperature-half', 'fa-solid fa-lightbulb', 'fa-solid fa-faucet-drip',
        'fa-solid fa-hammer', 'fa-solid fa-screwdriver-wrench', 'fa-solid fa-tape', 'fa-solid fa-ruler',
        'fa-solid fa-paintbrush', 'fa-solid fa-scissors',
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

                {{-- کارت‌های لیست‌ها --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                    @foreach ($lists as $list)
                        @php
                            $color = $list['color'] ?? 'blue-500';
                            $colors = $cardColors[$color] ?? $cardColors['blue-500'];
                            $listJson = json_encode($list, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
                        @endphp
                        <a href="{{ route('web.product.custom-lists.show', $list['id']) }}"
                           class="group bg-bg-primary border border-border-light rounded-2xl p-6 hover:shadow-md {{ $colors['border'] }} transition-all duration-200 block">
                            <div class="flex items-start gap-4 mb-4">
                                <div class="w-12 h-12 {{ $colors['bg'] }} rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                                    <i class="{{ $list['icon'] ?? 'fa-solid fa-clipboard-list' }} text-xl {{ $colors['icon'] }}"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-text-primary leading-snug mb-1 truncate">{{ $list['name'] ?? '' }}</h3>
                                    <p class="text-xs text-text-muted font-mono leading-normal">{{ $list['name_en'] ?? '' }}</p>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button onclick="event.preventDefault(); openEditListModal({{ $listJson }})"
                                            class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200"
                                            title="ویرایش لیست">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </button>
                                    <button onclick="event.preventDefault();"
                                            data-ajax
                                            data-confirm="آیا از حذف این لیست و همه آیتم‌های آن اطمینان دارید؟ این عملیات قابل بازگشت نیست."
                                            data-action="{{ route('api.v1.admin.product.custom-lists.destroy', $list['id']) }}"
                                            data-method="DELETE"
                                            data-on-success="reload"
                                            class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-red-600 hover:bg-red-50 rounded transition-all duration-200"
                                            title="حذف لیست">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-4 border-t border-border-light">
                                <span class="text-sm text-text-muted leading-normal">
                                    <i class="fa-solid fa-list ml-1"></i>
                                    {{ $list['items_count'] ?? 0 }} آیتم
                                </span>
                                <span class="text-xs text-text-muted leading-normal">{{ $list['created_at_jalali'] ?? '' }}</span>
                            </div>
                        </a>
                    @endforeach

                    {{-- کارت افزودن لیست جدید --}}
                    <button onclick="openCreateListModal()"
                            class="bg-bg-primary border-2 border-dashed border-border-medium rounded-2xl p-2 hover:border-primary hover:bg-primary/5 transition-all duration-200 flex flex-col items-center justify-center text-center min-h-[140px] group">
                        <div class="w-12 h-12 bg-bg-secondary rounded-full flex items-center justify-center mb-2 group-hover:bg-primary/10 transition-colors duration-200">
                            <i class="fa-solid fa-plus text-2xl text-text-muted group-hover:text-primary transition-colors duration-200"></i>
                        </div>
                        <span class="text-base font-semibold text-text-secondary leading-normal group-hover:text-primary transition-colors duration-200">ایجاد لیست جدید</span>
                    </button>

                </div>

            </div>
        </main>
    </div>

    {{-- مودال ایجاد/ویرایش لیست --}}
    @include('panel::products.modals.list-modal', [
        'suggestedIcons' => $suggestedIcons,
    ])

    @vite('Applications/AdminPanel/Resources/js/pages/product-lists.js')
</x-panel::layouts.app>
