@php
    $pageTitle = isset($tag) ? 'ویرایش برچسب: ' . ($tag['name'] ?? '') : 'افزودن برچسب جدید';
    $currentPage = 'app.tags';

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'مدیریت برچسب‌ها', 'url' => route('web.app.tags.index')],
        ['label' => $pageTitle],
    ];

    $actionButtons = [
        ['label' => 'بازگشت', 'url' => route('web.app.tags.index'), 'icon' => 'fa-solid fa-arrow-right', 'type' => 'outline'],
    ];

    $selectedColor = $tag['color'] ?? null;
    $selectedIcon = $tag['icon'] ?? null;
@endphp

<x-panel::layouts.app :title="$pageTitle">
    <div class="flex min-h-screen">
        <x-panel::dashboard.sidebar
            name="org.sidebar"
            :current-page="$currentPage"
            header-variant="back"
            module-title="مدیریت برچسب‌ها"
            module-icon="fa-solid fa-tags"
        />

        <main class="flex-1 flex flex-col">
            <x-panel::dashboard.header
                :title="$pageTitle"
                :breadcrumbs="$breadcrumbs"
                :actions="$actionButtons"
            />

            <div class="flex-1 p-6 lg:p-8">
                <form data-ajax
                      data-method="{{ isset($tag) ? 'PUT' : 'POST' }}"
                      action="{{ isset($tag) ? route('api.v1.admin.tags.update', ['tag' => $tag['id'] ?? null]) : route('api.v1.admin.tags.store') }}"
                      data-on-success="redirect"
                      class="space-y-6">
                    @csrf

                    <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
                        <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">اطلاعات برچسب</h2>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <x-panel::forms.input
                                class="min-w-[140px]"
                                name="name"
                                :value="$tag['name'] ?? ''"
                                label="نام برچسب"
                                placeholder="مثلاً: مدرس برتر"
                                required
                            />

                            <x-panel::forms.input
                                class="min-w-[140px] text-left"
                                name="slug"
                                :value="$tag['slug'] ?? ''"
                                label="اسلاگ (انگلیسی)"
                                placeholder="top-instructor"
                            />

                            <div class="lg:col-span-2">
                                <x-panel::forms.textarea
                                    class="min-w-[140px]"
                                    name="description"
                                    :value="$tag['description']['full'] ?? ''"
                                    label="توضیحات"
                                    placeholder="توضیحات اختیاری..."
                                    rows="3"
                                />
                            </div>

                            <div class="lg:col-span-2 space-y-6">
                                <x-panel::forms.color-field
                                    class="min-w-[140px]"
                                    name="color"
                                    label="رنگ برچسب"
                                    :selected="$selectedColor"
                                />

                                <x-panel::forms.icon-field
                                    class="min-w-[140px]"
                                    name="icon"
                                    label="آیکون برچسب"
                                    :selected="$selectedIcon"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <x-panel::ui.button type="submit" variant="green" icon="fa-solid fa-check">
                            {{ isset($tag) ? 'ذخیره تغییرات' : 'ایجاد برچسب' }}
                        </x-panel::ui.button>

                        <a href="{{ route('web.app.tags.index') }}"
                           class="inline-flex items-center gap-2 bg-bg-secondary text-text-secondary border border-border-medium px-xl py-md rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal">
                            <i class="fa-solid fa-times ml-2"></i>
                            <span>انصراف</span>
                        </a>

                        @if(isset($tag))
                            <button type="button"
                                    data-ajax
                                    data-method="DELETE"
                                    data-action="{{ route('api.v1.admin.tags.destroy', ['tag' => $tag['id']]) }}"
                                    data-on-success="redirect"
                                    data-redirect-url="{{ route('web.app.tags.index') }}"
                                    data-confirm="آیا از حذف این برچسب اطمینان دارید؟"
                                    class="mr-auto inline-flex items-center gap-2 bg-red-600 text-white px-xl py-md rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 text-base leading-normal">
                                <i class="fa-solid fa-trash"></i>
                                <span>حذف برچسب</span>
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </main>
    </div>

</x-panel::layouts.app>
