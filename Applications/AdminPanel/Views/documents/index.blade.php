@php
    $pageTitle = 'مدیریت اسناد و فایل‌ها';

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'مدیریت اسناد'],
    ];

    $fileTypeConfig = [
        0 => ['icon' => 'fa-solid fa-file-image', 'color' => 'text-purple-500', 'bg' => 'bg-purple-50'],    // IMAGE
        1 => ['icon' => 'fa-solid fa-file-video', 'color' => 'text-pink-500', 'bg' => 'bg-pink-50'],        // VIDEO
        2 => ['icon' => 'fa-solid fa-file-audio', 'color' => 'text-indigo-500', 'bg' => 'bg-indigo-50'],    // AUDIO
        3 => ['icon' => 'fa-solid fa-file-pdf', 'color' => 'text-red-500', 'bg' => 'bg-red-50'],            // DOCUMENT
        4 => ['icon' => 'fa-solid fa-file-zipper', 'color' => 'text-yellow-600', 'bg' => 'bg-yellow-50'],   // ARCHIVE
        5 => ['icon' => 'fa-solid fa-file', 'color' => 'text-gray-500', 'bg' => 'bg-gray-50'],              // OTHER
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
            <!-- Header with Search -->
            <header class="bg-bg-primary border-b border-border-light sticky top-0 z-30">
                <div class="px-6 py-5">
                    <!-- Top Row: Title & Actions -->
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-text-primary leading-tight mb-1">{{ $pageTitle }}</h1>
                            <x-panel::ui.breadcrumb :items="$breadcrumbs" />
                        </div>

                        <!-- Upload Button -->
                        <button
                            type="button"
                            data-modal-open="uploadModal"
                            class="bg-primary text-white px-5 py-2.5 rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 flex items-center gap-2 text-sm">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <span>آپلود فایل</span>
                        </button>
                    </div>

                    <!-- Search & Filters Row -->
                    <div class="flex flex-col lg:flex-row gap-3">
                        <!-- Search Input -->
                        <form method="GET" action="{{ route('web.documents.index') }}" class="flex-1">
                            <div class="flex-1 relative">
                                <i class="fa-solid fa-search absolute right-4 top-1/2 -translate-y-1/2 text-text-muted"></i>
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="جستجو در نام، تگ‌ها و عنوان فایل‌ها..."
                                    class="w-full pr-11 pl-4 py-3 border border-border-medium rounded-xl text-sm text-text-primary placeholder:text-text-muted focus:border-primary focus:shadow-focus outline-none transition-all duration-200"
                                >
                            </div>
                        </form>

                        <!-- Filters -->
                        <div class="flex items-center gap-2 flex-wrap">
                            <!-- File Type Filter -->
                            <div class="relative" data-dropdown-container>
                                <button
                                    data-dropdown-toggle="filter-type"
                                    class="flex items-center gap-2 px-4 py-3 border border-border-medium rounded-xl text-sm text-text-secondary hover:border-primary hover:text-primary transition-all duration-200">
                                    <i class="fa-solid fa-file-circle-question"></i>
                                    <span>نوع فایل</span>
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </button>
                            </div>

                            <!-- Show Temporary Toggle -->
                            <label
                                class="flex items-center gap-2 px-4 py-3 border border-border-medium rounded-xl text-sm text-text-secondary cursor-pointer hover:border-primary transition-all duration-200">
                                <input type="checkbox" id="is_temporary" name="is_temporary"
                                       class="accent-primary" {{ request('is_temporary') ? 'checked' : '' }}>
                                <i class="fa-solid fa-hourglass-half text-amber-500"></i>
                                <span>موقت‌ها</span>
                            </label>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="flex-1 p-6 lg:p-8">
                <!-- Main Folders Grid -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-text-primary">پوشه‌های اصلی</h2>
                        <button type="button" data-modal-open="folderModal"
                                class="text-sm text-primary hover:text-primary/80 font-medium flex items-center gap-1">
                            <i class="fa-solid fa-plus"></i>
                            <span>پوشه جدید</span>
                        </button>
                    </div>

                    <div class="flex flex-wrap gap-5">
                        @foreach ($folders as $folder)
                            @include('panel::documents.partials.folder-card', ['folder' => $folder])
                        @endforeach
                    </div>
                </div>

                <!-- Recent Files Table -->
                <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
                    <!-- Table Header -->
                    <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-text-primary">آخرین فایل‌ها</h2>
                        <div class="flex items-center gap-2">
                            <button
                                class="w-9 h-9 flex items-center justify-center rounded-lg text-text-muted bg-primary text-white transition-all duration-200"
                                title="نمای لیستی">
                                <i class="fa-solid fa-list"></i>
                            </button>
                            {{--<button class="w-9 h-9 flex items-center justify-center rounded-lg bg-primary text-white hover:bg-bg-secondary hover:text-primary" title="نمای کارتی">
                                <i class="fa-solid fa-grip"></i>
                            </button>--}}
                        </div>
                    </div>

                    <!-- Table Content -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-bg-secondary border-b border-border-light">
                            <tr>
                                <th class="text-right px-6 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider">فایل</th>
                                <th class="text-right px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider hidden lg:table-cell">پوشه</th>
                                <th class="text-right px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider hidden md:table-cell">تگ‌ها</th>
                                <th class="text-right px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider hidden sm:table-cell">حجم</th>
                                <th class="text-right px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider hidden lg:table-cell">تاریخ</th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider">عملیات</th>
                            </tr>
                            </thead>
                            @include('panel::documents.partials.files-table-body', [
                                'files' => $files,
                                'fileTypeConfig' => $fileTypeConfig,
                                'showFolderColumn' => true,
                                'showTagsColumn' => true,
                                'showSizeColumn' => true,
                                'showViewCountColumn' => false,
                                'showDateColumn' => true,
                                'dateType' => 'created_human',
                                'uploaderAvatarType' => 'avatar',
                                'emptyIcon' => 'fa-folder-open',
                                'emptyText' => 'هیچ فایلی یافت نشد',
                            ])
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if (isset($pagination) && !empty($pagination['total']) && $pagination['total'] > 0)
                        <x-panel::ui.pagination :pagination="$pagination"/>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <x-panel::modals.upload-modal :folders="$folders"/>

    @include('panel::documents.modals.folder-modal')

    @vite('resources/js/pages/documents.js')
</x-panel::layouts.app>
