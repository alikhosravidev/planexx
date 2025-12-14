@php
    $pageTitle = isset($department) ? 'ویرایش دپارتمان' : 'افزودن دپارتمان جدید';
    $listTitle = 'مدیریت دپارتمان‌ها';
    $listUrl = route('web.org.departments.index');
    $currentPage = 'org-departments';

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'ساختار سازمانی', 'url' => route('web.org.dashboard')],
        ['label' => $listTitle, 'url' => $listUrl],
        ['label' => $pageTitle],
    ];

    $actionButtons = [
        ['label' => 'بازگشت', 'url' => $listUrl, 'icon' => 'fa-solid fa-arrow-right', 'type' => 'outline'],
    ];

    $isActive = ! isset($department) || ! empty($department['is_active']);

    $icons = [
        'fa-building', 'fa-building-columns', 'fa-city', 'fa-landmark', 'fa-industry',
        'fa-chart-line', 'fa-chart-bar', 'fa-chart-pie', 'fa-bullhorn', 'fa-rectangle-ad',
        'fa-store', 'fa-shop', 'fa-cart-shopping', 'fa-basket-shopping', 'fa-tags',
        'fa-coins', 'fa-money-bill-wave', 'fa-credit-card', 'fa-wallet', 'fa-piggy-bank',
        'fa-calculator', 'fa-receipt', 'fa-file-invoice-dollar', 'fa-hand-holding-dollar', 'fa-sack-dollar',
        'fa-code', 'fa-laptop-code', 'fa-server', 'fa-database', 'fa-network-wired',
        'fa-microchip', 'fa-desktop', 'fa-cloud', 'fa-shield-halved', 'fa-bug',
        'fa-users', 'fa-user-tie', 'fa-user-group', 'fa-people-group', 'fa-handshake',
        'fa-id-card', 'fa-address-card', 'fa-clipboard-user', 'fa-user-plus', 'fa-users-gear',
        'fa-gears', 'fa-wrench', 'fa-screwdriver-wrench', 'fa-hammer', 'fa-toolbox',
        'fa-cogs', 'fa-robot', 'fa-warehouse', 'fa-boxes-stacked', 'fa-dolly',
        'fa-graduation-cap', 'fa-book', 'fa-book-open', 'fa-chalkboard-user', 'fa-school',
        'fa-user-graduate', 'fa-award', 'fa-certificate', 'fa-medal', 'fa-trophy',
        'fa-headset', 'fa-phone', 'fa-comments', 'fa-envelope', 'fa-paper-plane',
        'fa-life-ring', 'fa-circle-question', 'fa-message', 'fa-bell', 'fa-at',
        'fa-file-contract', 'fa-file-signature', 'fa-scale-balanced', 'fa-gavel', 'fa-stamp',
        'fa-pen-to-square', 'fa-file-pen', 'fa-clipboard-check', 'fa-folder-open', 'fa-briefcase',
        'fa-flask', 'fa-microscope', 'fa-atom', 'fa-dna', 'fa-lightbulb',
        'fa-magnifying-glass', 'fa-compass', 'fa-rocket', 'fa-satellite', 'fa-brain',
        'fa-truck', 'fa-plane', 'fa-ship', 'fa-train', 'fa-car',
        'fa-hospital', 'fa-house-medical', 'fa-heart-pulse', 'fa-stethoscope', 'fa-kit-medical',
        'fa-leaf', 'fa-tree', 'fa-sun', 'fa-bolt', 'fa-wind',
        'fa-star', 'fa-gem', 'fa-crown', 'fa-flag', 'fa-globe',
        'fa-map-location-dot', 'fa-location-dot', 'fa-calendar-days',
    ];

    $selectedColor = $department['color'] ?? 'blue-500';
    $selectedIcon = $department['icon'] ?? 'fa-building';

@endphp

<x-panel::layouts.app :title="$pageTitle">
    <div class="flex min-h-screen">
        <x-panel::dashboard.sidebar
            name="org.sidebar"
            :current-page="$currentPage"
            header-variant="back"
            module-title="ساختار سازمانی"
            module-icon="fa-solid fa-sitemap"
        />

        <main class="flex-1 flex flex-col">
            <x-panel::dashboard.header
                :title="$pageTitle"
                :breadcrumbs="$breadcrumbs"
                :actions="$actionButtons"
            />

            <div class="flex-1 p-6 lg:p-8">
                <form data-ajax
                      data-method="{{ isset($department) ? 'PUT' : 'POST' }}"
                      action="{{ isset($department) ? route('api.v1.admin.org.departments.update', ['department' => $department['id'] ?? null]) : route('api.v1.admin.org.departments.store') }}"
                      data-on-success="redirect"
                      data-redirect-url="{{ $listUrl }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                        <div class="lg:col-span-2 space-y-6">

                            <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
                                <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">اطلاعات پایه</h2>

                                <div class="space-y-4">
                                    <x-panel::forms.input
                                        class="min-w-[140px]"
                                        name="name"
                                        :value="$department['name'] ?? ''"
                                        label="نام دپارتمان"
                                        placeholder="نام دپارتمان را وارد کنید"
                                        required
                                    />

                                    <x-panel::forms.input
                                        class="min-w-[140px] text-left"
                                        name="code"
                                        :value="$department['code'] ?? ''"
                                        label="کد دپارتمان"
                                        placeholder="مثال: SALES"
                                    />

                                    @php
                                        $parentId = $department['parent']['id'] ?? null;
                                        $parentName = $department['parent']['name'] ?? null;
                                    @endphp

                                    <x-panel::organization.department.select
                                        name="parent_id"
                                        label="دپارتمان والد"
                                        placeholder="بدون والد (دپارتمان اصلی)"
                                        :value="$department['parent_id'] ?? null"
                                        class="min-w-[140px]"
                                        :options="$allDepartments ?? []"
                                    />

                                    <x-panel::forms.select
                                        name="type"
                                        label="نوع دپارتمان"
                                        :value="$department['type']['value'] ?? \App\Core\Organization\Enums\DepartmentTypeEnum::DEPARTMENT->value"
                                        class="min-w-[140px]"
                                        :options="$departmentTypes"
                                    />

                                    @php
                                        $managerId = $department['manager']['id'] ?? null;
                                        $managerName = $department['manager']['full_name'] ?? null;
                                    @endphp

                                    <x-panel::forms.select
                                        name="manager_id"
                                        label="مدیر دپارتمان"
                                        :value="$managerId"
                                        class="min-w-[140px]"
                                        :options="$managers"
                                    />

                                    <x-panel::forms.textarea
                                        class="min-w-[140px]"
                                        name="description"
                                        :value="$department['description']['full'] ?? ''"
                                        label="توضیحات"
                                        placeholder="توضیحات دپارتمان را وارد کنید"
                                        rows="4"
                                    />

                                    <!-- Image Upload Section -->
                                    <div id="image-upload-section" class="hidden">
                                        @php
                                            $currentImageUrl = $department['thumbnail']['file_url'] ?? null;
                                        @endphp
                                        <x-panel::ui.profile-image-upload :value="$currentImageUrl" label="تصویر" standalone/>
                                    </div>

                                    <!-- Icon and Color Section -->
                                    <div id="icon-color-section" class="space-y-6">
                                        <x-panel::forms.color-field
                                            class="min-w-[140px]"
                                            name="color"
                                            label="رنگ دپارتمان"
                                            :selected="$selectedColor"
                                        />

                                        <x-panel::forms.icon-field
                                            class="min-w-[140px]"
                                            name="icon"
                                            label="آیکون دپارتمان"
                                            :icons="$icons"
                                            :selected="$selectedIcon"
                                        />
                                    </div>

                                    <div class="flex items-center gap-6 px-lg py-3.5">
                                        <label class="text-sm text-text-secondary leading-normal min-w-[140px]">
                                            وضعیت
                                        </label>
                                        <x-panel::forms.radio
                                            name="is_active"
                                            :value="$isActive ? '1' : '0'"
                                            :options="['1' => 'فعال', '0' => 'غیرفعال']"
                                        />
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="space-y-6">

                            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                        <i class="fa-solid fa-lightbulb text-white"></i>
                                    </div>
                                    <h3 class="text-base font-semibold text-blue-800 leading-normal">راهنما</h3>
                                </div>
                                <ul class="space-y-3">
                                    <li class="flex items-start gap-2 text-sm text-blue-700 leading-relaxed">
                                        <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                                        <span>نام دپارتمان باید واضح و مشخص باشد</span>
                                    </li>
                                    <li class="flex items-start gap-2 text-sm text-blue-700 leading-relaxed">
                                        <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                                        <span>کد دپارتمان برای دسته‌بندی استفاده می‌شود</span>
                                    </li>
                                    <li class="flex items-start gap-2 text-sm text-blue-700 leading-relaxed">
                                        <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                                        <span>دپارتمان والد برای ایجاد ساختار درختی استفاده می‌شود</span>
                                    </li>
                                    <li class="flex items-start gap-2 text-sm text-blue-700 leading-relaxed">
                                        <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                                        <span>مدیر دپارتمان مسئول نظارت بر کارمندان است</span>
                                    </li>
                                    <li class="flex items-start gap-2 text-sm text-blue-700 leading-relaxed">
                                        <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                                        <span>برای هولدینگ و برند، تصویر الزامی است</span>
                                    </li>
                                    <li class="flex items-start gap-2 text-sm text-blue-700 leading-relaxed">
                                        <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                                        <span>برای دپارتمان و تیم، آیکون و رنگ الزامی است</span>
                                    </li>
                                </ul>
                            </div>

                        </div>

                    </div>

                    <div class="flex items-center gap-3 mt-6">
                        <x-panel::ui.button type="submit" variant="green" icon="fa-solid fa-check">
                            ذخیره دپارتمان
                        </x-panel::ui.button>
                        <a href="{{ $listUrl }}"
                           class="inline-flex items-center gap-2 bg-bg-secondary text-text-secondary border border-border-medium px-xl py-md rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal">
                            <i class="fa-solid fa-times ml-2"></i>
                            <span>انصراف</span>
                        </a>
                        @if(isset($department))
                            <button type="button"
                                    data-ajax
                                    data-confirm="آیا از حذف این دپارتمان اطمینان دارید؟"
                                    data-action="{{ route('api.v1.admin.org.departments.destroy', ['department' => $department['id']]) }}"
                                    data-method="DELETE"
                                    data-on-success="redirect"
                                    data-redirect-url="{{ $listUrl }}"
                                    class="inline-flex items-center gap-2 bg-red-600 text-white px-xl py-md rounded-lg font-medium hover:bg-red-700 transition-all duration-200 text-base leading-normal mr-auto">
                                <i class="fa-solid fa-trash ml-2"></i>
                                <span>حذف دپارتمان</span>
                            </button>
                        @endif
                    </div>

                </form>

            </div>
        </main>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Handle type-based field visibility
                function toggleTypeFields(type) {
                    const imageSection = document.getElementById('image-upload-section');
                    const iconColorSection = document.getElementById('icon-color-section');

                    if (type === '1' || type === '2') { // Holding or Brand
                        imageSection.classList.remove('hidden');
                        iconColorSection.classList.add('hidden');
                    } else { // Department or Team
                        imageSection.classList.add('hidden');
                        iconColorSection.classList.remove('hidden');
                    }
                }

                // Initial state
                const typeSelect = document.querySelector('select[name="type"]');
                if (typeSelect) {
                    toggleTypeFields(typeSelect.value);

                    typeSelect.addEventListener('change', function () {
                        toggleTypeFields(this.value);
                    });
                }
            });
        </script>
    @endpush
</x-panel::layouts.app>
