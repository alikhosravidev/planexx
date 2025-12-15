@php
    $pageTitle = isset($role) ? 'ویرایش نقش: ' . ($role['title'] ?? $role['name']) : 'افزودن نقش جدید';
    $currentPage = 'org-roles';

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'ساختار سازمانی', 'url' => route('web.org.dashboard')],
        ['label' => 'مدیریت نقش‌ها', 'url' => route('web.org.roles.index')],
        ['label' => $pageTitle],
    ];

    $actionButtons = [];
    if (isset($role)) {
        $actionButtons[] = ['label' => 'مدیریت دسترسی‌ها', 'url' => route('web.org.roles.permissions', ['role' => $role['id']]), 'icon' => 'fa-solid fa-key', 'type' => 'outline'];
    }
    $actionButtons[] = ['label' => 'بازگشت', 'url' => route('web.org.roles.index'), 'icon' => 'fa-solid fa-arrow-right', 'type' => 'outline'];
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

        <main class="flex-1 flex flex-col min-w-0">
            <x-panel::dashboard.header
                :title="$pageTitle"
                :breadcrumbs="$breadcrumbs"
                :actions="$actionButtons"
            />

            <div class="flex-1 p-6 lg:p-8">
                <form data-ajax
                      data-method="{{ isset($role) ? 'PUT' : 'POST' }}"
                      action="{{ isset($role) ? route('api.v1.admin.org.roles.update', ['role' => $role['id']]) : route('api.v1.admin.org.roles.store') }}"
                      data-on-success="redirect"
                      class="space-y-6">
                    @csrf

                    <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
                        <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">اطلاعات نقش</h2>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <x-panel::forms.input
                                class="min-w-[140px]"
                                name="title"
                                :value="$role['title'] ?? ''"
                                label="عنوان نقش (فارسی)"
                                placeholder="مدیر سیستم، مدیر ارشد، کارمند"
                            />

                            <x-panel::forms.input
                                class="min-w-[140px] text-left"
                                name="name"
                                :value="$role['name'] ?? ''"
                                label="نام نقش (انگلیسی)"
                                placeholder="admin, manager, employee"
                                required
                            />
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <x-panel::ui.button type="submit" variant="green" icon="fa-solid fa-check">
                            {{ isset($role) ? 'ذخیره تغییرات' : 'ایجاد نقش' }}
                        </x-panel::ui.button>

                        <a href="{{ route('web.org.roles.index') }}"
                           class="inline-flex items-center gap-2 bg-bg-secondary text-text-secondary border border-border-medium px-xl py-md rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal">
                            <i class="fa-solid fa-times ml-2"></i>
                            <span>انصراف</span>
                        </a>

                        @if(isset($role))
                            <button type="button"
                                    data-ajax
                                    data-method="DELETE"
                                    data-action="{{ route('api.v1.admin.org.roles.destroy', ['role' => $role['id']]) }}"
                                    data-on-success="redirect"
                                    data-redirect-url="{{ route('web.org.roles.index') }}"
                                    data-confirm="آیا از حذف این نقش اطمینان دارید؟"
                                    class="mr-auto inline-flex items-center gap-2 bg-red-600 text-white px-xl py-md rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 text-base leading-normal">
                                <i class="fa-solid fa-trash"></i>
                                <span>حذف نقش</span>
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </main>
    </div>
</x-panel::layouts.app>
