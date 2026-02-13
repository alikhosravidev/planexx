@php
    $title = 'جزئیات محصول';
    $currentPage = 'products-list';

    $actionButtons = [
        ['label' => 'ویرایش', 'url' => route('web.product.products.edit', $product['id']), 'icon' => 'fa-solid fa-pen', 'type' => 'primary'],
        ['label' => 'بازگشت به لیست', 'url' => route('web.product.products.index'), 'icon' => 'fa-solid fa-arrow-right', 'type' => 'outline'],
    ];

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'محصولات و لیست‌ها', 'url' => route('web.product.products.index')],
        ['label' => 'محصولات', 'url' => route('web.product.products.index')],
        ['label' => $product['title'] ?? 'جزئیات محصول'],
    ];

    $statusLabels = [
        'active' => ['label' => 'فعال', 'class' => 'bg-green-50 text-green-700 border-green-200'],
        'out_of_stock' => ['label' => 'ناموجود', 'class' => 'bg-red-50 text-red-700 border-red-200'],
        'draft' => ['label' => 'پیش‌نویس', 'class' => 'bg-yellow-50 text-yellow-700 border-yellow-200'],
        'inactive' => ['label' => 'غیرفعال', 'class' => 'bg-gray-100 text-gray-700 border-gray-200'],
    ];

    $statusValue = is_array($product['status'] ?? null)
        ? ($product['status']['value'] ?? 'draft')
        : ($product['status'] ?? 'draft');
    $status = $statusLabels[$statusValue] ?? $statusLabels['draft'];
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
                :title="$product['title'] ?? $title"
                :breadcrumbs="$breadcrumbs"
                :actions="$actionButtons"
            />

            <div class="flex-1 p-6 lg:p-8">

                {{-- هدر محصول --}}
                <div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
                    <div class="flex items-start gap-6">
                        <div class="w-20 h-20 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-box text-primary text-3xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-2">
                                <h2 class="text-2xl font-bold text-text-primary leading-snug">{{ $product['title'] ?? '' }}</h2>
                                <span class="inline-flex items-center gap-1.5 {{ $status['class'] }} px-3 py-1 rounded-lg text-xs font-medium border leading-normal">
                                    <i class="fa-solid fa-circle text-[6px]"></i>
                                    {{ $status['label'] }}
                                </span>
                            </div>
                            <div class="flex items-center gap-4 text-sm text-text-muted">
                                @if(!empty($product['created_at_jalali']))
                                    <span><i class="fa-solid fa-calendar ml-1"></i> ایجاد: {{ $product['created_at_jalali'] }}</span>
                                @endif
                                @if(!empty($product['updated_at_jalali']))
                                    <span><i class="fa-solid fa-clock ml-1"></i> بروزرسانی: {{ $product['updated_at_jalali'] }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- اطلاعات اصلی --}}
                    <div class="bg-white border border-border-light rounded-2xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-border-light">
                            <h3 class="text-lg font-semibold text-text-primary leading-snug">اطلاعات اصلی</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between py-2 border-b border-border-light">
                                    <span class="text-sm text-text-secondary leading-normal">کد محصول (SKU)</span>
                                    <span class="text-base text-text-primary font-medium font-mono leading-normal">{{ $product['sku'] ?? '-' }}</span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b border-border-light">
                                    <span class="text-sm text-text-secondary leading-normal">دسته‌بندی</span>
                                    <div>
                                        @if(!empty($product['categories']))
                                            @foreach($product['categories'] as $category)
                                                <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-600 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal ml-1">{{ $category['name'] }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-text-muted">-</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-text-secondary leading-normal">نامک (Slug)</span>
                                    <span class="text-base text-text-primary font-mono leading-normal" dir="ltr">{{ $product['slug'] ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- قیمت و انبار --}}
                    <div class="bg-white border border-border-light rounded-2xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-border-light">
                            <h3 class="text-lg font-semibold text-text-primary leading-snug">قیمت‌گذاری و انبار</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between py-2 border-b border-border-light">
                                    <span class="text-sm text-text-secondary leading-normal">قیمت (ریال)</span>
                                    <span class="text-xl text-text-primary font-bold leading-normal">{{ $product['price'] ?? '-' }}</span>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-text-secondary leading-normal">قیمت با تخفیف</span>
                                    <span class="text-base text-green-600 font-medium leading-normal">{{ $product['sale_price'] ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </main>
    </div>
</x-panel::layouts.app>
