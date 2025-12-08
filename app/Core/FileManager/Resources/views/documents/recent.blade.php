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
                                data-search-input>
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
                            <tbody class="divide-y divide-border-light">
                                @forelse ($files as $file)
                                    @php
                                        $typeConfig = $fileTypeConfig[$file['file_type']['value']] ?? $fileTypeConfig[5];
                                    @endphp
                                    <tr class="hover:bg-bg-secondary/50 transition-colors duration-200" data-file-id="{{ $file['id'] }}">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="relative flex-shrink-0">
                                                    <div class="w-11 h-11 {{ $typeConfig['bg'] }} rounded-xl flex items-center justify-center">
                                                        <i class="{{ $typeConfig['icon'] }} {{ $typeConfig['color'] }} text-lg"></i>
                                                    </div>
                                                    @if (isset($file['uploader']))
                                                        <div class="absolute -bottom-1 -right-2 w-6 h-6 rounded-full border-2 border-white overflow-hidden">
                                                            <img src="{{ $file['uploader']['image_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($file['uploader']['full_name'] ?? 'U') }}" alt="" class="w-full h-full object-cover">
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

                                        <td class="px-4 py-4 hidden lg:table-cell">
                                            <span class="text-sm text-text-secondary">{{ $file['folder']['name'] ?? '-' }}</span>
                                        </td>

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

                                        <td class="px-4 py-4 hidden sm:table-cell">
                                            <span class="text-sm text-text-muted">{{ $file['view_count'] ?? 0 }} بار</span>
                                        </td>

                                        <td class="px-4 py-4 hidden lg:table-cell">
                                            <span class="text-sm text-text-muted">{{ $file['last_viewed_at']['jalali'] ?? '-' }}</span>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-end gap-1">
                                                <button
                                                    data-ajax
                                                    data-method="POST"
                                                    data-action="{{ route('api.v1.admin.file-manager.files.favorite.toggle', ['fileId' => $file['id']]) }}"
                                                    data-on-success="custom"
                                                    custom-action="toggleFavorite"
                                                    class="w-8 h-8 flex items-center justify-center rounded-lg transition-all duration-200 {{ $file['is_favorite'] ? 'text-amber-500 bg-amber-50' : 'text-text-muted hover:text-amber-500 hover:bg-amber-50' }}"
                                                    title="{{ $file['is_favorite'] ? 'حذف از علاقه‌مندی' : 'افزودن به علاقه‌مندی' }}">
                                                    <i class="{{ $file['is_favorite'] ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                                </button>

                                                <a
                                                    href="{{ route('web.documents.files.download', ['id' => $file['id']]) }}"
                                                    class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200"
                                                    title="دانلود">
                                                    <i class="fa-solid fa-download"></i>
                                                </a>

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
                                                <i class="fa-solid fa-clock-rotate-left text-6xl text-text-muted/30 mb-4"></i>
                                                <p class="text-text-muted">هیچ فایل اخیری وجود ندارد</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if (isset($pagination) && !empty($pagination['total']) && $pagination['total'] > 0)
                        <x-ui.pagination :pagination="$pagination" />
                    @endif
                </div>
            </div>
        </main>
    </div>

    <x-file-manager.upload-modal :folders="$folders" />
</x-layouts.app>
