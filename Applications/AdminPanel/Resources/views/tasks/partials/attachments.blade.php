@php
    $allAttachments = $task['attachments'];
@endphp

<div class="bg-bg-primary border border-border-light rounded-2xl p-6">
    <div class="flex items-center justify-between mb-5">
        <h3 class="text-base font-bold text-text-primary flex items-center gap-2">
            <i class="fa-solid fa-paperclip text-slate-600"></i>
            پیوست‌ها
            @if(!empty($allAttachments))
                <span class="text-xs font-normal text-text-muted">({{ count($allAttachments) }})</span>
            @endif
        </h3>
        <button data-modal-open="attachmentModal" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium transition-colors">
            <i class="fa-solid fa-plus ml-1"></i>
            افزودن پیوست
        </button>
    </div>

    @if(!empty($allAttachments))
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($allAttachments as $attachment)
                @php
                    $fileTypeLabel = $attachment['file_type_label'] ?? 'file';
                    $typeStyles = [
                        'document' => ['bg' => 'bg-red-100', 'text' => 'text-red-600', 'icon' => 'fa-file-pdf'],
                        'excel' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'icon' => 'fa-file-excel'],
                        'word' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'icon' => 'fa-file-word'],
                        'image' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'icon' => 'fa-file-image'],
                        'video' => ['bg' => 'bg-pink-100', 'text' => 'text-pink-600', 'icon' => 'fa-file-video'],
                        'audio' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600', 'icon' => 'fa-file-audio'],
                        'archive' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600', 'icon' => 'fa-file-zipper'],
                    ];
                    $style = $typeStyles[$fileTypeLabel] ?? ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'icon' => 'fa-file'];
                @endphp
                <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-all cursor-pointer group">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 {{ $style['bg'] }} {{ $style['text'] }}">
                        <i class="fa-solid {{ $style['icon'] }} text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-text-primary text-sm font-medium truncate">{{ $attachment['title'] ?? $attachment['original_name'] ?? '-' }}</h4>
                        <p class="text-text-muted text-xs">
                            {{ $attachment['file_size_human'] ?? '-' }}
                        </p>
                    </div>
                    <a
                        href="{{ route('web.documents.files.download', ['id' => $attachment['id']]) }}"
                        class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200"
                        title="دانلود">
                        <i class="fa-solid fa-download text-slate-400 group-hover:text-indigo-600 transition-colors"></i>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-8 text-center">
            <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                <i class="fa-solid fa-paperclip text-2xl text-slate-400"></i>
            </div>
            <p class="text-text-muted text-sm">هیچ پیوستی وجود ندارد</p>
        </div>
    @endif
</div>
