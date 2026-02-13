@php
    $title = 'افزودن محصول جدید';
    $currentPage = 'products-list';

    $actionButtons = [
        ['label' => 'بازگشت به لیست', 'url' => route('web.product.products.index'), 'icon' => 'fa-solid fa-arrow-right', 'type' => 'outline'],
    ];

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'محصولات و لیست‌ها', 'url' => route('web.product.products.index')],
        ['label' => 'محصولات', 'url' => route('web.product.products.index')],
        ['label' => 'افزودن محصول جدید'],
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
                      action="{{ route('api.v1.admin.product.products.store') }}"
                      data-method="POST"
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
                                class="min-w-[140px]"
                            />

                            <x-panel::forms.input
                                name="slug"
                                label="نامک (Slug)"
                                placeholder="product-slug"
                                direction="ltr"
                            />

                            <x-panel::forms.input
                                name="sku"
                                label="کد محصول (SKU)"
                                placeholder="PRD-001"
                                :required="true"
                                direction="ltr"
                                class="min-w-[140px]"
                            />

                            <x-panel::forms.select
                                name="category_id"
                                label="دسته‌بندی"
                                :options="$categories ?? []"
                                placeholder="انتخاب کنید"
                            />

                            <x-panel::forms.radio
                                name="status"
                                label="وضعیت محصول"
                                :options="$statusOptions"
                                value="active"
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
                                class="min-w-[140px]"
                            />

                            <x-panel::forms.input
                                name="sale_price"
                                label="قیمت با تخفیف"
                                placeholder="خالی بگذارید اگر تخفیف ندارد"
                            />
                        </div>
                    </div>

                    {{-- تصویر محصول --}}
                    <div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
                        <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">تصویر محصول</h2>

                        <div class="border-2 border-dashed border-border-medium rounded-xl p-8 text-center hover:border-primary transition-all duration-200 cursor-pointer"
                             onclick="document.getElementById('productImage').click()">
                            <input type="file" id="productImage" name="image" accept="image/*" class="hidden">
                            <div class="w-16 h-16 bg-bg-secondary rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-cloud-upload-alt text-2xl text-text-muted"></i>
                            </div>
                            <p class="text-base text-text-primary font-medium leading-normal mb-1">تصویر محصول را انتخاب کنید</p>
                            <p class="text-sm text-text-muted leading-normal">فرمت‌های مجاز: JPG, PNG, WebP - حداکثر ۲ مگابایت</p>
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
                            <span>ذخیره و انتشار</span>
                        </button>
                    </div>

                </form>

            </div>
        </main>
    </div>
</x-panel::layouts.app>
