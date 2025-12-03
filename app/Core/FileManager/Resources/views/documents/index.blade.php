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

<x-layouts.app :title="$pageTitle">
    <div class="flex min-h-screen">
        <x-dashboard.sidebar
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
                        <div class="flex-1 relative">
                            <i class="fa-solid fa-search absolute right-4 top-1/2 -translate-y-1/2 text-text-muted"></i>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="جستجو در نام، تگ‌ها و عنوان فایل‌ها..."
                                class="w-full pr-11 pl-4 py-3 border border-border-medium rounded-xl text-sm text-text-primary placeholder:text-text-muted focus:border-primary focus:shadow-focus outline-none transition-all duration-200"
                                data-search-input>
                        </div>

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
                            <label class="flex items-center gap-2 px-4 py-3 border border-border-medium rounded-xl text-sm text-text-secondary cursor-pointer hover:border-primary transition-all duration-200">
                                <input type="checkbox" name="is_temporary" class="accent-primary" {{ request('is_temporary') ? 'checked' : '' }}>
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
                        <button type="button" data-modal-open="folderModal" class="text-sm text-primary hover:text-primary/80 font-medium flex items-center gap-1">
                            <i class="fa-solid fa-plus"></i>
                            <span>پوشه جدید</span>
                        </button>
                    </div>

                    <div class="flex flex-wrap gap-5">
                        @foreach ($folders as $folder)
                            <x-ui.folder-card
                                :folder="$folder"
                                :url="route('web.documents.folder', ['folderId' => $folder['id']])"
                            />
                        @endforeach
                    </div>
                </div>

                <!-- Recent Files Table -->
                <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
                    <!-- Table Header -->
                    <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-text-primary">آخرین فایل‌ها</h2>
                        <div class="flex items-center gap-2">
                            <button class="w-9 h-9 flex items-center justify-center rounded-lg text-text-muted hover:bg-bg-secondary hover:text-primary transition-all duration-200" title="نمای لیستی">
                                <i class="fa-solid fa-list"></i>
                            </button>
                            <button class="w-9 h-9 flex items-center justify-center rounded-lg bg-primary text-white" title="نمای کارتی">
                                <i class="fa-solid fa-grip"></i>
                            </button>
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
                            <tbody class="divide-y divide-border-light">
                                @forelse ($files as $file)
                                    @php
                                        $typeConfig = $fileTypeConfig[$file['file_type']] ?? $fileTypeConfig[5];
                                    @endphp
                                    <tr class="hover:bg-bg-secondary/50 transition-colors duration-200" data-file-id="{{ $file['id'] }}">
                                        <!-- File Info -->
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="relative flex-shrink-0">
                                                    <div class="w-11 h-11 {{ $typeConfig['bg'] }} rounded-xl flex items-center justify-center">
                                                        <i class="{{ $typeConfig['icon'] }} {{ $typeConfig['color'] }} text-lg"></i>
                                                    </div>
                                                    @if (isset($file['uploader']))
                                                        <div class="absolute -bottom-1 -right-2 w-6 h-6 rounded-full border-2 border-white overflow-hidden" title="{{ $file['uploader']['full_name'] ?? '' }}">
                                                            <img src="{{ $file['uploader']['image_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($file['uploader']['full_name'] ?? 'U') }}" alt="{{ $file['uploader']['full_name'] ?? '' }}" class="w-full h-full object-cover">
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-sm font-medium text-text-primary truncate leading-tight">{{ $file['title'] ?? $file['original_name'] }}</span>
                                                        @if ($file['is_temporary'])
                                                            <span class="px-1.5 py-0.5 bg-amber-50 text-amber-600 rounded text-[10px] font-medium">موقت</span>
                                                        @endif
                                                    </div>
                                                    <p class="text-xs text-text-muted truncate leading-normal">{{ $file['original_name'] }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Folder -->
                                        <td class="px-4 py-4 hidden lg:table-cell">
                                            <span class="text-sm text-text-secondary">{{ $file['folder']['name'] ?? '-' }}</span>
                                        </td>

                                        <!-- Tags -->
                                        <td class="px-4 py-4 hidden md:table-cell">
                                            <div class="flex flex-wrap gap-1">
                                                @if (isset($file['tags']) && count($file['tags']) > 0)
                                                    @foreach (array_slice($file['tags'], 0, 2) as $tag)
                                                        <span class="px-2 py-0.5 bg-bg-secondary text-text-muted rounded text-xs">{{ $tag['name'] }}</span>
                                                    @endforeach
                                                    @if (count($file['tags']) > 2)
                                                        <span class="px-2 py-0.5 bg-bg-secondary text-text-muted rounded text-xs">+{{ count($file['tags']) - 2 }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-sm text-text-muted">-</span>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Size -->
                                        <td class="px-4 py-4 hidden sm:table-cell">
                                            <span class="text-sm text-text-muted">{{ $file['file_size_human'] ?? '-' }}</span>
                                        </td>

                                        <!-- Date -->
                                        <td class="px-4 py-4 hidden lg:table-cell">
                                            <span class="text-sm text-text-muted">{{ $file['created_at']['jalali'] ?? '-' }}</span>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-end gap-1">
                                                <!-- Favorite -->
                                                <button
                                                    data-ajax
                                                    data-method="POST"
                                                    action="{{ route('api.v1.admin.file-manager.files.favorite.toggle', ['fileId' => $file['id']]) }}"
                                                    data-on-success="custom"
                                                    data-action="toggleFavorite"
                                                    class="w-8 h-8 flex items-center justify-center rounded-lg transition-all duration-200 text-text-muted hover:text-amber-500 hover:bg-amber-50"
                                                    title="افزودن به علاقه‌مندی">
                                                    <i class="fa-regular fa-star"></i>
                                                </button>

                                                <!-- Download -->
                                                <a
                                                    href="{{ route('web.documents.files.download', ['id' => $file['id']]) }}"
                                                    class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200"
                                                    title="دانلود">
                                                    <i class="fa-solid fa-download"></i>
                                                </a>

                                                <!-- Delete -->
                                                <button
                                                    data-ajax
                                                    data-method="DELETE"
                                                    action="{{ route('api.v1.admin.file-manager.files.destroy', ['file' => $file['id']]) }}"
                                                    data-on-success="remove"
                                                    data-target="[data-file-id='{{ $file['id'] }}']"
                                                    data-confirm="آیا از حذف این فایل اطمینان دارید؟"
                                                    class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-red-500 hover:bg-red-50 rounded-lg transition-all duration-200"
                                                    title="حذف">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="fa-solid fa-folder-open text-6xl text-text-muted/30 mb-4"></i>
                                                <p class="text-text-muted">هیچ فایلی یافت نشد</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if (isset($pagination) && !empty($pagination['total']) && $pagination['total'] > 0)
                        <x-ui.pagination :pagination="$pagination" />
                    @endif
                </div>
            </div>
        </main>
    </div>

    <!-- Upload Modal -->
    <x-file-manager.upload-modal :folders="$folders" />

    <!-- Folder Modal -->
    <x-file-manager.folder-modal :folders="$folders" />
</x-layouts.app>
