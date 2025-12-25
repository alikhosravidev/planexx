@props([
    'value' => null,
    'item' => null,
    'options' => [],
])

@php
    $title = $value ?? $item['title'] ?? '-';
    $slug = $item['slug'] ?? '';
    $followUpsCount = $item['follow_ups_count'] ?? 0;
    $attachmentsCount = $item['files_count'] ?? 0;
    $priority = ($item['priority']['value'] ?? 1) ?? 1;

    $priorityStyles = [
        0 => ['bg' => 'bg-slate-100', 'dot' => 'bg-slate-400'],
        1 => ['bg' => 'bg-blue-100', 'dot' => 'bg-blue-500'],
        2 => ['bg' => 'bg-orange-100', 'dot' => 'bg-orange-500'],
        3 => ['bg' => 'bg-red-100', 'dot' => 'bg-red-500'],
    ];

    $priorityStyle = $priorityStyles[$priority] ?? $priorityStyles[1];
@endphp

<div class="flex items-start gap-3">
    <div class="flex-shrink-0">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $priorityStyle['bg'] }}">
            <div class="w-3 h-3 rounded-full {{ $priorityStyle['dot'] }}"></div>
        </div>
    </div>
    <div class="flex-1 min-w-0">
        <h3 class="text-base font-bold text-text-primary mb-1 truncate group-hover:text-indigo-600 transition-colors">
            {{ $title }}
        </h3>
        <div class="flex flex-wrap items-center gap-3 text-xs text-text-muted">
            @if($slug)
                <span class="font-mono bg-gray-100 px-1.5 py-0.5 rounded text-[11px]">{{ $slug }}</span>
            @endif
            @if($followUpsCount > 0)
                <span class="flex items-center gap-1">
                    <i class="fa-solid fa-message"></i>
                    {{ $followUpsCount }} پیگیری
                </span>
            @endif
            @if($attachmentsCount > 0)
                <span class="flex items-center gap-1">
                    <i class="fa-solid fa-paperclip"></i>
                    {{ $attachmentsCount }} پیوست
                </span>
            @endif
        </div>
    </div>
</div>
