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

    $colors = [
        'blue-500', 'blue-600', 'blue-700', 'sky-500', 'sky-600', 'cyan-500', 'cyan-600',
        'teal-500', 'teal-600', 'green-500', 'green-600', 'emerald-500', 'emerald-600', 'lime-500',
        'yellow-500', 'amber-500', 'amber-600', 'orange-500', 'orange-600',
        'red-500', 'red-600', 'rose-500', 'rose-600', 'pink-500', 'pink-600', 'fuchsia-500',
        'purple-500', 'purple-600', 'violet-500', 'violet-600', 'indigo-500', 'indigo-600',
        'slate-500', 'slate-600', 'gray-500', 'gray-600', 'zinc-500', 'zinc-600', 'stone-500',
    ];

    $icons = [
        'fa-tag', 'fa-tags', 'fa-star', 'fa-heart', 'fa-bookmark',
        'fa-bolt', 'fa-gem', 'fa-fire', 'fa-thumbs-up', 'fa-trophy',
        'fa-check', 'fa-circle', 'fa-square', 'fa-bell', 'fa-lightbulb',
        'fa-rocket', 'fa-leaf', 'fa-tree', 'fa-sun', 'fa-moon',
        'fa-user', 'fa-users', 'fa-graduation-cap', 'fa-briefcase', 'fa-chart-line',
        'fa-comments', 'fa-paper-plane', 'fa-calendar-days', 'fa-globe', 'fa-crown',
    ];

    $selectedColor = $tag['color'] ?? 'blue-500';
    $selectedIcon = $tag['icon'] ?? 'fa-tag';
@endphp

<x-layouts.app :title="$pageTitle">
    <div class="flex min-h-screen">
        <x-dashboard.sidebar
            name="org.sidebar"
            :current-page="$currentPage"
            header-variant="back"
            module-title="مدیریت برچسب‌ها"
            module-icon="fa-solid fa-tags"
        />

        <main class="flex-1 flex flex-col">
            <x-dashboard.header
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
                            <x-forms.input
                                class="min-w-[140px]"
                                name="name"
                                :value="$tag['name'] ?? ''"
                                label="نام برچسب"
                                placeholder="مثلاً: مدرس برتر"
                                required
                            />

                            <x-forms.input
                                class="min-w-[140px]"
                                name="slug"
                                :value="$tag['slug'] ?? ''"
                                label="اسلاگ (انگلیسی)"
                                placeholder="top-instructor"
                            />

                            <div class="lg:col-span-2">
                                <x-forms.textarea
                                    class="min-w-[140px]"
                                    name="description"
                                    :value="$tag['description']['full'] ?? ''"
                                    label="توضیحات"
                                    placeholder="توضیحات اختیاری..."
                                    rows="3"
                                />
                            </div>

                            <div class="lg:col-span-2 space-y-6">
                                <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
                                    <div class="flex">
                                        <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-start leading-normal pt-4">
                                            رنگ برچسب
                                        </label>
                                        <div class="flex-1 px-lg py-3.5 flex flex-wrap gap-2">
                                            @foreach($colors as $color)
                                                <label class="cursor-pointer color-option">
                                                    <input type="radio" name="color" value="{{ $color }}" class="peer hidden" {{ $color === $selectedColor ? 'checked' : '' }}>
                                                    <div class="w-8 h-8 bg-{{ $color }} rounded-lg border-2 border-transparent peer-checked:border-gray-800 peer-checked:ring-2 peer-checked:ring-gray-300 transition-all flex items-center justify-center">
                                                        <i class="fa-solid fa-check text-white text-xs opacity-0 peer-checked:opacity-100 check-icon"></i>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
                                    <div class="flex">
                                        <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-start leading-normal pt-4">
                                            آیکون برچسب
                                        </label>
                                        <div class="flex-1 px-lg py-3.5">
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($icons as $icon)
                                                    <label class="cursor-pointer icon-option">
                                                        <input type="radio" name="icon" value="{{ $icon }}" class="peer hidden" {{ $icon === $selectedIcon ? 'checked' : '' }}>
                                                        <div class="w-10 h-10 bg-bg-secondary rounded-lg border-2 border-transparent peer-checked:border-primary peer-checked:bg-primary/10 transition-all flex items-center justify-center hover:bg-gray-100">
                                                            <i class="fa-solid {{ $icon }} text-lg text-text-secondary peer-checked:text-primary"></i>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <x-ui.button type="submit" variant="green" icon="fa-solid fa-check">
                            {{ isset($tag) ? 'ذخیره تغییرات' : 'ایجاد برچسب' }}
                        </x-ui.button>

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

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.color-option input').forEach(input => {
                input.addEventListener('change', function() {
                    document.querySelectorAll('.color-option .check-icon').forEach(icon => {
                        icon.classList.add('opacity-0');
                        icon.classList.remove('opacity-100');
                    });
                    if (this.checked) {
                        this.nextElementSibling.querySelector('.check-icon').classList.remove('opacity-0');
                        this.nextElementSibling.querySelector('.check-icon').classList.add('opacity-100');
                    }
                });
            });

            document.querySelectorAll('.icon-option input').forEach(input => {
                input.addEventListener('change', function() {
                    document.querySelectorAll('.icon-option > div').forEach(div => {
                        div.classList.remove('border-primary', 'bg-primary/10');
                        div.classList.add('border-transparent');
                        div.querySelector('i').classList.remove('text-primary');
                        div.querySelector('i').classList.add('text-text-secondary');
                    });
                    if (this.checked) {
                        this.nextElementSibling.classList.add('border-primary', 'bg-primary/10');
                        this.nextElementSibling.classList.remove('border-transparent');
                        this.nextElementSibling.querySelector('i').classList.add('text-primary');
                        this.nextElementSibling.querySelector('i').classList.remove('text-text-secondary');
                    }
                });
            });

            const checkedColor = document.querySelector('.color-option input:checked');
            if (checkedColor) {
                checkedColor.nextElementSibling.querySelector('.check-icon').classList.remove('opacity-0');
                checkedColor.nextElementSibling.querySelector('.check-icon').classList.add('opacity-100');
            }

            const checkedIcon = document.querySelector('.icon-option input:checked');
            if (checkedIcon) {
                checkedIcon.nextElementSibling.classList.add('border-primary', 'bg-primary/10');
                checkedIcon.nextElementSibling.classList.remove('border-transparent');
                checkedIcon.nextElementSibling.querySelector('i').classList.add('text-primary');
                checkedIcon.nextElementSibling.querySelector('i').classList.remove('text-text-secondary');
            }
        });
    </script>
    @endpush
</x-layouts.app>
