@php
    $pageTitle = 'فایل‌های مورد علاقه';

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'مدیریت اسناد', 'url' => route('web.documents.index')],
        ['label' => 'علاقه‌مندی‌ها'],
    ];

    $fileTypeConfig = [
        'pdf' => ['icon' => 'fa-solid fa-file-pdf', 'color' => 'text-red-500', 'bg' => 'bg-red-50'],
        'word' => ['icon' => 'fa-solid fa-file-word', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50'],
        'excel' => ['icon' => 'fa-solid fa-file-excel', 'color' => 'text-green-600', 'bg' => 'bg-green-50'],
        'powerpoint' => ['icon' => 'fa-solid fa-file-powerpoint', 'color' => 'text-orange-500', 'bg' => 'bg-orange-50'],
        'image' => ['icon' => 'fa-solid fa-file-image', 'color' => 'text-purple-500', 'bg' => 'bg-purple-50'],
        'video' => ['icon' => 'fa-solid fa-file-video', 'color' => 'text-pink-500', 'bg' => 'bg-pink-50'],
        'audio' => ['icon' => 'fa-solid fa-file-audio', 'color' => 'text-indigo-500', 'bg' => 'bg-indigo-50'],
        'zip' => ['icon' => 'fa-solid fa-file-zipper', 'color' => 'text-yellow-600', 'bg' => 'bg-yellow-50'],
        'default' => ['icon' => 'fa-solid fa-file', 'color' => 'text-gray-500', 'bg' => 'bg-gray-50'],
    ];

    $folderColorClasses = [
        'purple' => 'text-purple-500',
        'pink' => 'text-pink-500',
        'green' => 'text-green-500',
        'blue' => 'text-blue-500',
        'yellow' => 'text-yellow-500',
        'red' => 'text-red-500',
        'indigo' => 'text-indigo-500',
        'gray' => 'text-gray-500',
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
            <header class="bg-bg-primary border-b border-border-light sticky top-0 z-30">
                <div class="px-6 py-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                                <i class="fa-solid fa-star text-amber-500 text-xl"></i>
                            </div>
                            <div>
                                <h1 class="text-xl font-bold text-text-primary leading-tight">{{ $pageTitle }}</h1>
                                <nav class="flex items-center gap-2 text-xs text-text-muted mt-0.5">
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
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1.5 bg-amber-50 text-amber-600 rounded-lg text-sm font-medium">
                                {{ count($files) }} فایل
                            </span>
                        </div>
                    </div>

                    <div class="relative max-w-xl">
                        <i class="fa-solid fa-search absolute right-4 top-1/2 -translate-y-1/2 text-text-muted"></i>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="جستجو در علاقه‌مندی‌ها..."
                            class="w-full pr-11 pl-4 py-2.5 border border-border-medium rounded-xl text-sm text-text-primary placeholder:text-text-muted focus:border-primary focus:shadow-focus outline-none transition-all duration-200"
                        >
                    </div>
                </div>
            </header>

            <div class="flex-1 p-6 lg:p-8">
                <!-- Grid View of Favorite Files -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse ($files as $file)
                        @php
                            // Determine file type based on extension or mime type
                            $extension = strtolower(pathinfo($file['original_name'], PATHINFO_EXTENSION));
                            $fileType = 'default';

                            if (in_array($extension, ['pdf'])) {
                                $fileType = 'pdf';
                            } elseif (in_array($extension, ['doc', 'docx'])) {
                                $fileType = 'word';
                            } elseif (in_array($extension, ['xls', 'xlsx'])) {
                                $fileType = 'excel';
                            } elseif (in_array($extension, ['ppt', 'pptx'])) {
                                $fileType = 'powerpoint';
                            } elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                $fileType = 'image';
                            } elseif (in_array($extension, ['mp4', 'avi', 'mov'])) {
                                $fileType = 'video';
                            } elseif (in_array($extension, ['mp3', 'wav', 'ogg'])) {
                                $fileType = 'audio';
                            } elseif (in_array($extension, ['zip', 'rar', '7z'])) {
                                $fileType = 'zip';
                            }

                            $typeConfig = $fileTypeConfig[$fileType] ?? $fileTypeConfig['default'];
                            $folderColor = $file['folder']['color'] ?? 'gray';
                            $folderColorClass = $folderColorClasses[$folderColor] ?? 'text-gray-500';
                        @endphp
                        <div class="bg-bg-primary border border-border-light rounded-2xl p-5 hover:shadow-md hover:border-amber-200 transition-all duration-200 group" data-file-id="{{ $file['id'] }}">

                            <!-- File Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="relative">
                                    <div class="w-14 h-14 {{ $typeConfig['bg'] }} rounded-xl flex items-center justify-center">
                                        <i class="{{ $typeConfig['icon'] }} {{ $typeConfig['color'] }} text-2xl"></i>
                                    </div>
                                    <!-- Uploader Avatar -->
                                    @if (isset($file['uploader']))
                                        <div class="absolute -bottom-1 -right-2 w-7 h-7 rounded-full border-2 border-white overflow-hidden" title="{{ $file['uploader']['full_name'] ?? '' }}">
                                            <img src="{{ $file['uploader']['image_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($file['uploader']['full_name'] ?? 'U') }}" alt="{{ $file['uploader']['full_name'] ?? '' }}" class="w-full h-full object-cover">
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1">
                                    <!-- Remove from Favorites -->
                                    <button
                                        data-ajax
                                        data-method="POST"
                                        data-action="{{ route('api.v1.admin.file-manager.files.favorite.toggle', ['fileId' => $file['id']]) }}"
                                        data-on-success="custom"
                                        custom-action="toggleFavorite"
                                        class="w-8 h-8 flex items-center justify-center text-amber-500 hover:bg-amber-50 rounded-lg transition-all duration-200"
                                        title="حذف از علاقه‌مندی">
                                        <i class="fa-solid fa-star"></i>
                                    </button>
                                    <!-- More Actions -->
                                    <div class="relative" data-dropdown-container>
                                        <button
                                            data-dropdown-toggle="fav-actions-{{ $file['id'] }}"
                                            class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <div
                                            id="fav-actions-{{ $file['id'] }}"
                                            data-dropdown
                                            class="hidden absolute top-full left-0 mt-2 w-44 bg-bg-primary border border-border-light rounded-xl shadow-lg overflow-hidden z-50">
                                            <div class="p-2">
                                                <button class="w-full text-right px-3 py-2 text-sm hover:bg-bg-secondary rounded-lg flex items-center gap-2">
                                                    <i class="fa-solid fa-eye w-4"></i> مشاهده
                                                </button>
                                                <a
                                                    href="{{ route('web.documents.files.download', ['id' => $file['id']]) }}"
                                                    class="w-full text-right px-3 py-2 text-sm hover:bg-bg-secondary rounded-lg flex items-center gap-2 text-text-secondary hover:text-text-primary">
                                                    <i class="fa-solid fa-download w-4"></i> دانلود
                                                </a>
                                                <button class="w-full text-right px-3 py-2 text-sm hover:bg-bg-secondary rounded-lg flex items-center gap-2">
                                                    <i class="fa-solid fa-link w-4"></i> کپی لینک
                                                </button>
                                                @if (isset($file['folder']['id']))
                                                    <a
                                                        href="{{ route('web.documents.folder', ['folderId' => $file['folder']['id']]) }}"
                                                        class="w-full text-right px-3 py-2 text-sm hover:bg-bg-secondary rounded-lg flex items-center gap-2 text-text-secondary hover:text-text-primary">
                                                        <i class="fa-solid fa-folder-open w-4"></i> رفتن به پوشه
                                                    </a>
                                                @endif
                                                <button
                                                    data-ajax
                                                    data-method="DELETE"
                                                    data-action="{{ route('api.v1.admin.file-manager.files.destroy', ['file' => $file['id']]) }}"
                                                    data-on-success="remove"
                                                    data-target="[data-file-id='{{ $file['id'] }}']"
                                                    data-confirm="آیا از حذف این فایل اطمینان دارید؟"
                                                    class="w-full text-right px-3 py-2 text-sm hover:bg-bg-secondary rounded-lg flex items-center gap-2 text-red-500">
                                                    <i class="fa-solid fa-trash w-4"></i> حذف
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- File Info -->
                            <h3 class="text-sm font-semibold text-text-primary mb-1 leading-tight line-clamp-1">{{ $file['title'] ?? $file['original_name'] }}</h3>
                            <p class="text-xs text-text-muted mb-3 truncate">{{ $file['original_name'] }}</p>

                            <!-- Tags -->
                            <div class="flex flex-wrap gap-1 mb-4">
                                @if (isset($file['tags']) && count($file['tags']) > 0)
                                    @foreach (array_slice($file['tags'], 0, 3) as $tag)
                                        <span class="px-2 py-0.5 bg-bg-secondary text-text-muted rounded text-xs">{{ $tag['name'] }}</span>
                                    @endforeach
                                @endif
                            </div>

                            <!-- Meta Info -->
                            <div class="flex items-center justify-between text-xs text-text-muted pt-3 border-t border-border-light">
                                <div class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-folder {{ $folderColorClass }}"></i>
                                    <span>{{ $file['folder']['name'] ?? 'بدون پوشه' }}</span>
                                </div>
                                <span>{{ $file['file_size_human'] ?? '-' }}</span>
                            </div>

                        </div>
                    @empty
                        <!-- Empty State -->
                        <div class="col-span-full text-center py-16">
                            <div class="w-20 h-20 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-regular fa-star text-3xl text-amber-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-text-primary mb-2">هنوز فایلی به علاقه‌مندی‌ها اضافه نشده</h3>
                            <p class="text-sm text-text-muted mb-6">با کلیک روی آیکون ستاره، فایل‌های مهم را به این لیست اضافه کنید</p>
                            <a href="{{ route('web.documents.index') }}" class="bg-primary text-white px-5 py-2.5 rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 text-sm inline-flex items-center gap-2">
                                <i class="fa-solid fa-folder-open"></i>
                                <span>مرور فایل‌ها</span>
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    <x-FileManager::upload-modal :folders="$folders" />

    @vite('resources/js/pages/documents.js')
</x-layouts.app>
