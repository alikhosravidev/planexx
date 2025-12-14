@props([
    'title' => '',
    'summary' => '',
    'department' => '',
    'departmentColor' => 'blue',
    'author' => '',
    'authorPosition' => '',
    'date' => '',
    'views' => 0,
    'rating' => 0,
    'attachments' => 0,
    'tags' => [],
    'url' => '#',
])

@php
$colorClasses = [
    'green' => 'bg-green-50 text-green-700',
    'slate' => 'bg-slate-50 text-slate-700',
    'pink' => 'bg-pink-50 text-pink-700',
    'orange' => 'bg-orange-50 text-orange-700',
    'blue' => 'bg-blue-50 text-blue-700',
    'purple' => 'bg-purple-50 text-purple-700',
];
$deptColor = $colorClasses[$departmentColor] ?? $colorClasses['blue'];
@endphp

<a href="{{ $url }}" class="block bg-white rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-200 border border-border-light" {{ $attributes }}>
    <div class="flex items-start justify-between gap-3 mb-3">
        <span class="inline-flex items-center gap-1.5 {{ $deptColor }} px-3 py-1 rounded-lg text-xs font-medium">
            {{ $department }}
        </span>
        <div class="flex items-center gap-1 text-amber-500">
            <i class="fa-solid fa-star text-xs"></i>
            <span class="text-xs font-semibold">{{ number_format($rating, 1) }}</span>
        </div>
    </div>
    
    <h3 class="text-base font-bold text-text-primary leading-snug mb-2">{{ $title }}</h3>
    <p class="text-sm text-text-secondary leading-relaxed mb-4 line-clamp-2">{{ $summary }}</p>
    
    <div class="flex items-center justify-between pt-3 border-t border-border-light">
        <div class="flex items-center gap-2">
            <x-panel::ui.avatar :name="$author" size="sm" />
            <div>
                <div class="text-xs font-medium text-text-primary">{{ $author }}</div>
                <div class="text-[10px] text-text-muted">{{ $authorPosition }}</div>
            </div>
        </div>
        
        <div class="flex items-center gap-3 text-text-muted text-xs">
            @if($attachments > 0)
                <span class="flex items-center gap-1">
                    <i class="fa-solid fa-paperclip"></i>
                    {{ $attachments }}
                </span>
            @endif
            <span class="flex items-center gap-1">
                <i class="fa-solid fa-eye"></i>
                {{ $views }}
            </span>
        </div>
    </div>
    
    @if(!empty($tags))
        <div class="flex flex-wrap gap-1.5 mt-3">
            @foreach(array_slice($tags, 0, 3) as $tag)
                <span class="text-[10px] bg-bg-secondary text-text-muted px-2 py-0.5 rounded">{{ $tag }}</span>
            @endforeach
        </div>
    @endif
</a>
