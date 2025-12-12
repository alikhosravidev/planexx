@php
    $showFolderColumn = $showFolderColumn ?? false;
    $showTagsColumn = $showTagsColumn ?? true;
    $showSizeColumn = $showSizeColumn ?? false;
    $showViewCountColumn = $showViewCountColumn ?? false;
    $showDateColumn = $showDateColumn ?? false;
    $showTemporaryIcon = $showTemporaryIcon ?? false;
    $dateType = $dateType ?? null; // created_human, created_jalali, last_viewed_jalali
    $uploaderAvatarType = $uploaderAvatarType ?? 'avatar'; // avatar | image_url
    $emptyIcon = $emptyIcon ?? 'fa-folder-open';
    $emptyText = $emptyText ?? 'هیچ فایلی یافت نشد';

    $colspan = 2;
    if ($showFolderColumn) {
        $colspan++;
    }
    if ($showTagsColumn) {
        $colspan++;
    }
    if ($showSizeColumn) {
        $colspan++;
    }
    if ($showViewCountColumn) {
        $colspan++;
    }
    if ($showDateColumn) {
        $colspan++;
    }
@endphp

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

                    @if ($showTemporaryIcon)
                        <div class="absolute -top-1 -left-1 w-5 h-5 bg-amber-500 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-hourglass-half text-white text-[10px]"></i>
                        </div>
                    @endif

                    @if (isset($file['uploader']))
                        <div class="absolute -bottom-1 -right-2 w-6 h-6 rounded-full border-2 border-white overflow-hidden"
                             title="{{ $file['uploader']['full_name'] ?? '' }}">
                            @if ($uploaderAvatarType === 'image_url')
                                <img
                                    src="{{ $file['uploader']['image_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($file['uploader']['full_name'] ?? 'U') }}"
                                    alt="{{ $file['uploader']['full_name'] ?? '' }}" class="w-full h-full object-cover">
                            @else
                                <img
                                    src="{{ $file['uploader']['avatar']['file_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($file['uploader']['full_name'] ?? 'U') }}"
                                    alt="{{ $file['uploader']['full_name'] ?? '' }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                    @endif
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span
                            class="text-sm font-medium text-text-primary truncate leading-tight">{{ $file['title'] ?? $file['original_name'] }}</span>
                        @if ($file['is_temporary'])
                            <span class="px-1.5 py-0.5 bg-amber-50 text-amber-600 rounded text-[10px] font-medium">موقت</span>
                        @endif
                    </div>
                    <p class="text-xs text-text-muted truncate leading-normal">{{ $file['original_name'] }}</p>
                </div>
            </div>
        </td>

        @if ($showFolderColumn)
            <td class="px-4 py-4 hidden lg:table-cell">
                <span class="text-sm text-text-secondary">{{ $file['folder']['name'] ?? '-' }}</span>
            </td>
        @endif

        @if ($showTagsColumn)
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
        @endif

        @if ($showSizeColumn)
            <td class="px-4 py-4 hidden sm:table-cell">
                <span class="text-sm text-text-muted">{{ $file['file_size_human'] ?? '-' }}</span>
            </td>
        @endif

        @if ($showViewCountColumn)
            <td class="px-4 py-4 hidden sm:table-cell">
                <span class="text-sm text-text-muted">{{ $file['view_count'] ?? 0 }} بار</span>
            </td>
        @endif

        @if ($showDateColumn)
            <td class="px-4 py-4 hidden lg:table-cell">
                @if ($dateType === 'created_human')
                    <span class="text-sm text-text-muted">{{ $file['created_at']['human']['short'] ?? '-' }}</span>
                @elseif ($dateType === 'created_jalali')
                    <span class="text-sm text-text-muted">{{ $file['created_at']['jalali'] ?? '-' }}</span>
                @elseif ($dateType === 'last_viewed_jalali')
                    <span class="text-sm text-text-muted">{{ $file['last_viewed_at']['jalali'] ?? '-' }}</span>
                @else
                    <span class="text-sm text-text-muted">-</span>
                @endif
            </td>
        @endif

        <td class="px-6 py-4">
            <div class="flex items-center justify-end gap-1">
                <button
                    data-copy-text="{{ $file['file_url'] ?? route('web.documents.files.download', ['id' => $file['id']]) }}"
                    class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-all duration-200"
                    title="کپی لینک">
                    <i class="fa-solid fa-copy"></i>
                </button>

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
                    data-action="{{ route('api.v1.admin.file-manager.files.destroy', ['file' => $file['id']]) }}"
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
        <td colspan="{{ $colspan }}" class="px-6 py-12 text-center">
            <div class="flex flex-col items-center justify-center">
                <i class="fa-solid {{ $emptyIcon }} text-6xl text-text-muted/30 mb-4"></i>
                <p class="text-text-muted">{{ $emptyText }}</p>
            </div>
        </td>
    </tr>
@endforelse
</tbody>
