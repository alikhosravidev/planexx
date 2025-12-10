@php
    $pageTitle = $folder['name'] ?? 'پوشه';

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'مدیریت اسناد', 'url' => route('web.documents.index')],
    ];

    // Add parent folders to breadcrumb
    if (isset($folder['parent']['name'])) {
        $breadcrumbs[] = ['label' => $folder['parent']['name'], 'url' => route('web.documents.folder', ['folderId' => $folder['parent']['id']])];
    }

    $breadcrumbs[] = ['label' => $folder['name'] ?? 'پوشه'];

    $fileTypeConfig = [
        0 => ['icon' => 'fa-solid fa-file-image', 'color' => 'text-purple-500', 'bg' => 'bg-purple-50'],
        1 => ['icon' => 'fa-solid fa-file-video', 'color' => 'text-pink-500', 'bg' => 'bg-pink-50'],
        2 => ['icon' => 'fa-solid fa-file-audio', 'color' => 'text-indigo-500', 'bg' => 'bg-indigo-50'],
        3 => ['icon' => 'fa-solid fa-file-pdf', 'color' => 'text-red-500', 'bg' => 'bg-red-50'],
        4 => ['icon' => 'fa-solid fa-file-zipper', 'color' => 'text-yellow-600', 'bg' => 'bg-yellow-50'],
        5 => ['icon' => 'fa-solid fa-file', 'color' => 'text-gray-500', 'bg' => 'bg-gray-50'],
    ];

    $folderColorClasses = [
        'purple' => 'bg-purple-50 text-purple-500 border-purple-200',
        'pink' => 'bg-pink-50 text-pink-500 border-pink-200',
        'green' => 'bg-green-50 text-green-500 border-green-200',
        'blue' => 'bg-blue-50 text-blue-500 border-blue-200',
        'slate' => 'bg-slate-50 text-slate-500 border-slate-200',
        'amber' => 'bg-amber-50 text-amber-500 border-amber-200',
        'orange' => 'bg-sky-50 text-sky-500 border-sky-200',
        'teal' => 'bg-teal-50 text-teal-500 border-teal-200',
        'red' => 'bg-red-50 text-red-500 border-red-200',
    ];

    $color = $folder['color'] ?? 'blue';
    $folderColorClass = $folderColorClasses[$color] ?? $folderColorClasses['blue'];
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
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-12 h-12 {{ $folderColorClass }} rounded-xl flex items-center justify-center border">
                                    <i class="fa-solid {{ $folder['icon'] ?? 'fa-folder' }} text-2xl"></i>
                                </div>
                                <div>
                                    <h1 class="text-2xl font-bold text-text-primary leading-tight">{{ $pageTitle }}</h1>
                                    <p class="text-sm text-text-muted">{{ $folder['description']['full'] ?? '' }}</p>
                                </div>
                            </div>
                            <x-ui.breadcrumb :items="$breadcrumbs" />
                        </div>

                        <div class="flex items-center gap-2">
                            <button
                                class="text-red-500"
                                data-ajax
                                data-method="DELETE"
                                data-confirm="تمامی فایل‌های این پوشه بعد از حذف به پوشه آرشیو منتقل می شوند، آیا اطمینان دارید؟"
                                data-action="{{ route('api.v1.admin.file-manager.folders.destroy', $folder['id']) }}"
                                data-on-success="redirect"
                                data-redirect-url="{{ route('web.documents.index') }}"
                                title="حذف پوشه">
                                <i class="fa-solid fa-trash"></i>
                            </button>

                            <button
                                class="text-orange-500"
                                type="button"
                                data-modal-open="folderModal">
                                <i class="fa-solid fa-edit"></i>
                            </button>

                            <x-ui.button
                                variant="primary"
                                icon="fa-cloud-arrow-up"
                                data-modal-open="uploadModal"
                                type="button"
                                size="sm"
                                class="px-5 py-2.5 hover:-translate-y-0.5 transition-all duration-200 text-sm">
                                آپلود فایل
                            </x-ui.button>
                        </div>
                    </div>

                    <div class="flex flex-col lg:flex-row gap-3">
                        <div class="flex-1 relative">
                            <i class="fa-solid fa-search absolute right-4 top-1/2 -translate-y-1/2 text-text-muted"></i>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="جستجو در این پوشه..."
                                class="w-full pr-11 pl-4 py-3 border border-border-medium rounded-xl text-sm text-text-primary placeholder:text-text-muted focus:border-primary focus:shadow-focus outline-none transition-all duration-200"
                            >
                        </div>
                    </div>
                </div>
            </header>

            <div class="flex-1 p-6 lg:p-8">


                <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-text-primary">فایل‌های این پوشه</h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-bg-secondary border-b border-border-light">
                                <tr>
                                    <th class="text-right px-6 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider">فایل</th>
                                    <th class="text-right px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider hidden md:table-cell">تگ‌ها</th>
                                    <th class="text-right px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider hidden sm:table-cell">حجم</th>
                                    <th class="text-right px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider hidden lg:table-cell">تاریخ</th>
                                    <th class="text-left px-6 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider">عملیات</th>
                                </tr>
                            </thead>
                            @include('FileManager::documents.partials.files-table-body', [
                                'files' => $files,
                                'fileTypeConfig' => $fileTypeConfig,
                                'showFolderColumn' => false,
                                'showTagsColumn' => true,
                                'showSizeColumn' => true,
                                'showViewCountColumn' => false,
                                'showDateColumn' => true,
                                'dateType' => 'created_jalali',
                                'uploaderAvatarType' => 'avatar',
                                'emptyIcon' => 'fa-folder-open',
                                'emptyText' => 'این پوشه خالی است',
                            ])
                        </table>
                    </div>

                    @if (isset($pagination) && !empty($pagination['total']) && $pagination['total'] > 0)
                        <x-ui.pagination :pagination="$pagination" />
                    @endif
                </div>
            </div>
        </main>
    </div>

    <x-FileManager::upload-modal :currentFolder="$folder"/>

    <x-FileManager::folder-modal :folder="$folder"/>

    @vite('resources/js/pages/documents.js')
</x-layouts.app>
