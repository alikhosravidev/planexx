@php
    $pageTitle = 'اخیراً مشاهده شده';

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'مدیریت اسناد', 'url' => route('web.documents.index')],
        ['label' => 'اخیراً مشاهده شده'],
    ];

    $fileTypeConfig = [
        0 => ['icon' => 'fa-solid fa-file-image', 'color' => 'text-purple-500', 'bg' => 'bg-purple-50'],
        1 => ['icon' => 'fa-solid fa-file-video', 'color' => 'text-pink-500', 'bg' => 'bg-pink-50'],
        2 => ['icon' => 'fa-solid fa-file-audio', 'color' => 'text-indigo-500', 'bg' => 'bg-indigo-50'],
        3 => ['icon' => 'fa-solid fa-file-pdf', 'color' => 'text-red-500', 'bg' => 'bg-red-50'],
        4 => ['icon' => 'fa-solid fa-file-zipper', 'color' => 'text-yellow-600', 'bg' => 'bg-yellow-50'],
        5 => ['icon' => 'fa-solid fa-file', 'color' => 'text-gray-500', 'bg' => 'bg-gray-50'],
    ];
@endphp

<x-panel::layouts.app :title="$pageTitle">
    <div class="flex min-h-screen">
        <x-panel::dashboard.sidebar
            name="documents.sidebar"
            :current-page="$currentPage"
            header-variant="back"
            module-title="مدیریت اسناد"
            module-icon="fa-solid fa-folder-open"
        />

        <main class="flex-1 flex flex-col min-w-0">
            <header class="bg-bg-primary border-b border-border-light sticky top-0 z-30">
                <div class="px-6 py-5">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center border border-blue-200">
                                    <i class="fa-solid fa-clock-rotate-left text-2xl"></i>
                                </div>
                                <h1 class="text-2xl font-bold text-text-primary leading-tight">{{ $pageTitle }}</h1>
                            </div>
                            <nav class="flex items-center gap-2 text-xs text-text-muted">
                                @foreach ($breadcrumbs as $index => $crumb)
                                    @if ($index > 0)
                                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                                    @endif
                                    @if (isset($crumb['url']) && $index < count($breadcrumbs) - 1)
                                        <a href="{{ $crumb['url'] }}" class="hover:text-primary transition-colors leading-normal">{{ $crumb['label'] }}</a>
                                    @else
                                        <span class="text-text-primary font-medium leading-normal">{{ $crumb['label'] }}</span>
                                    @endif
                                @endforeach
                            </nav>
                        </div>

                        <button
                            type="button" data-modal-open="uploadModal"
                            class="bg-primary text-white px-5 py-2.5 rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 flex items-center gap-2 text-sm">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <span>آپلود فایل</span>
                        </button>
                    </div>

                    <div class="flex flex-col lg:flex-row gap-3">
                        <div class="flex-1 relative">
                            <i class="fa-solid fa-search absolute right-4 top-1/2 -translate-y-1/2 text-text-muted"></i>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="جستجو در فایل‌های اخیر..."
                                class="w-full pr-11 pl-4 py-3 border border-border-medium rounded-xl text-sm text-text-primary placeholder:text-text-muted focus:border-primary focus:shadow-focus outline-none transition-all duration-200"
                            >
                        </div>
                    </div>
                </div>
            </header>

            <div class="flex-1 p-6 lg:p-8">
                <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-text-primary">فایل‌های اخیر</h2>
                        <div class="text-sm text-text-muted">
                            مرتب شده بر اساس آخرین بازدید
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-bg-secondary border-b border-border-light">
                                <tr>
                                    <th class="text-right px-6 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider">فایل</th>
                                    <th class="text-right px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider hidden lg:table-cell">پوشه</th>
                                    <th class="text-right px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider hidden md:table-cell">تگ‌ها</th>
                                    <th class="text-right px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider hidden sm:table-cell">تعداد بازدید</th>
                                    <th class="text-right px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider hidden lg:table-cell">آخرین بازدید</th>
                                    <th class="text-left px-6 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider">عملیات</th>
                                </tr>
                            </thead>
                            @include('panel::documents.partials.files-table-body', [
                                'files' => $files,
                                'fileTypeConfig' => $fileTypeConfig,
                                'showFolderColumn' => true,
                                'showTagsColumn' => true,
                                'showSizeColumn' => false,
                                'showViewCountColumn' => true,
                                'showDateColumn' => true,
                                'dateType' => 'last_viewed_jalali',
                                'uploaderAvatarType' => 'image_url',
                                'emptyIcon' => 'fa-clock-rotate-left',
                                'emptyText' => 'هیچ فایل اخیری وجود ندارد',
                            ])
                        </table>
                    </div>

                    @if (isset($pagination) && !empty($pagination['total']) && $pagination['total'] > 0)
                        <x-panel::ui.pagination :pagination="$pagination" />
                    @endif
                </div>
            </div>
        </main>
    </div>

    <x-panel::modals.upload-modal :folders="$folders" />

    @vite('Applications/AdminPanel/Resources/js/pages/documents.js')
</x-panel::layouts.app>
