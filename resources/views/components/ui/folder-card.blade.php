@props([
    'folder' => [],
    'url' => '#',
])

@php
    $colorClasses = [
        'purple' => ['main' => '#7C3AED', 'tab' => '#A78BFA'],
        'pink'   => ['main' => '#EC4899', 'tab' => '#F472B6'],
        'green'  => ['main' => '#10B981', 'tab' => '#34D399'],
        'blue'   => ['main' => '#3B82F6', 'tab' => '#60A5FA'],
        'slate'  => ['main' => '#64748B', 'tab' => '#94A3B8'],
        'amber'  => ['main' => '#F59E0B', 'tab' => '#FBBF24'],
    ];
    
    $colors = $colorClasses[$folder['color'] ?? 'blue'] ?? $colorClasses['blue'];
    $filesCount = $folder['files_count'] ?? 0;
    $name = $folder['name'] ?? 'پوشه';
@endphp

<a href="{{ $url }}" class="group block w-[132px]">
    <div class="relative transition-all duration-200 group-hover:-translate-y-1 group-hover:drop-shadow-lg">
        <!-- SVG Folder -->
        <svg viewBox="0 0 1024 1024" class="w-full h-auto" xmlns="http://www.w3.org/2000/svg">
            <path d="M853.333333 256H469.333333l-85.333333-85.333333H170.666667c-46.933333 0-85.333333 38.4-85.333334 85.333333v170.666667h853.333334v-85.333334c0-46.933333-38.4-85.333333-85.333334-85.333333z" fill="{{ $colors['tab'] }}"></path>
            <path d="M853.333333 256H170.666667c-46.933333 0-85.333333 38.4-85.333334 85.333333v426.666667c0 46.933333 38.4 85.333333 85.333334 85.333333h682.666666c46.933333 0 85.333333-38.4 85.333334-85.333333V341.333333c0-46.933333-38.4-85.333333-85.333334-85.333333z" fill="{{ $colors['main'] }}"></path>
        </svg>
        <!-- Folder Content Overlay -->
        <div class="absolute inset-0 flex flex-col items-center justify-center pt-[28%] px-2 pb-1">
            <h3 class="text-[11px] sm:text-xs font-semibold text-white leading-tight text-center line-clamp-2 drop-shadow-sm">{{ $name }}</h3>
            <p class="text-[9px] sm:text-[10px] text-white/80 mt-0.5">{{ $filesCount }} فایل</p>
        </div>
    </div>
</a>
