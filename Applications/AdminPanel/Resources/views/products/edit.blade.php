@php
    $title = 'ویرایش محصول';
    $currentPage = 'products-list';

    $actionButtons = [
        ['label' => 'بازگشت به لیست', 'url' => route('web.product.products.index'), 'icon' => 'fa-solid fa-arrow-right', 'type' => 'outline'],
    ];

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'محصولات و لیست‌ها', 'url' => route('web.product.products.index')],
        ['label' => 'محصولات', 'url' => route('web.product.products.index')],
        ['label' => 'ویرایش: ' . ($product['title'] ?? '')],
    ];

    $statusOptions = [
        'active' => 'فعال',
        'draft' => 'پیش‌نویس',
        'inactive' => 'غیرفعال',
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

                <form data-ajax
                      action="{{ route('api.v1.admin.product.products.update', $product['id']) }}"
                      data-method="PUT"
                      data-on-success="redirect"
                      data-redirect="{{ route('web.product.products.index') }}"
                      enctype="multipart/form-data">
                    @csrf

                    {{-- اطلاعات اصلی محصول --}}
                    <div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
                        <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">اطلاعات اصلی محصول</h2>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <x-panel::forms.input
                                name="title"
                                label="عنوان محصول"
                                placeholder="عنوان محصول را وارد کنید"
                                :required="true"
                                :value="$product['title'] ?? ''"
                                class="min-w-[140px]"
                            />

                            <x-panel::forms.input
                                name="slug"
                                label="نامک (Slug)"
                                placeholder="product-slug"
                                direction="ltr"
                                :value="$product['slug'] ?? ''"
                            />

                            <x-panel::forms.input
                                name="sku"
                                label="کد محصول (SKU)"
                                placeholder="PRD-001"
                                :required="true"
                                direction="ltr"
                                :value="$product['sku'] ?? ''"
                                class="min-w-[140px]"
                            />

                            <x-panel::forms.select
                                name="category_id"
                                label="دسته‌بندی"
                                :options="$categories ?? []"
                                placeholder="انتخاب کنید"
                                :value="$product['category_id'] ?? ''"
                            />

                            <x-panel::forms.radio
                                name="status"
                                label="وضعیت محصول"
                                :options="$statusOptions"
                                :value="$product['status']['value'] ?? $product['status'] ?? 'active'"
                            />
                        </div>
                    </div>

                    {{-- قیمت‌گذاری --}}
                    <div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
                        <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">قیمت‌گذاری و انبار</h2>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <x-panel::forms.input
                                name="price"
                                label="قیمت (ریال)"
                                placeholder="۰"
                                :required="true"
                                :value="$product['price'] ?? ''"
                                class="min-w-[140px]"
                            />

                            <x-panel::forms.input
                                name="sale_price"
                                label="قیمت با تخفیف"
                                placeholder="خالی بگذارید اگر تخفیف ندارد"
                                :value="$product['sale_price'] ?? ''"
                            />
                        </div>
                    </div>

                    {{-- دکمه‌های عملیاتی --}}
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('web.product.products.index') }}"
                           class="bg-bg-secondary text-text-secondary border border-border-medium px-xl py-md rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal">
                            انصراف
                        </a>
                        <button type="submit"
                                class="bg-primary text-white px-xl py-md rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 flex items-center gap-2 text-base leading-normal">
                            <i class="fa-solid fa-check ml-2"></i>
                            <span>ذخیره تغییرات</span>
                        </button>
                    </div>

                </form>

            </div>
        </main>
    </div>
</x-panel::layouts.app>
