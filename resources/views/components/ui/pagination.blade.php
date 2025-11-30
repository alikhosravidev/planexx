@props([
    'from' => null,
    'to' => null,
    'total' => null,
    'label' => 'رکورد',
    'current' => 1,
    'prevUrl' => '#',
    'nextUrl' => '#',
    'hasPrev' => false,
    'hasNext' => false,
])

<div class="px-6 py-4 border-t border-border-light flex items-center justify-between bg-white">
    <div class="text-sm text-text-secondary leading-normal">
        @if($from !== null && $to !== null && $total !== null)
            نمایش <span class="font-semibold text-text-primary">{{ $from }}</span> تا <span class="font-semibold text-text-primary">{{ $to }}</span> از <span class="font-semibold text-text-primary">{{ $total }}</span> {{ $label }}
        @endif
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ $hasPrev ? $prevUrl : '#' }}"
           class="px-3 py-2 border border-border-medium rounded-lg text-sm text-text-secondary hover:bg-bg-secondary transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed {{ $hasPrev ? '' : 'pointer-events-none opacity-50' }}">
            <i class="fa-solid fa-chevron-right"></i>
        </a>
        <span class="px-3 py-2 bg-primary text-white rounded-lg text-sm font-medium">{{ $current }}</span>
        <a href="{{ $hasNext ? $nextUrl : '#' }}"
           class="px-3 py-2 border border-border-medium rounded-lg text-sm text-text-secondary hover:bg-bg-secondary transition-all duration-200 {{ $hasNext ? '' : 'pointer-events-none opacity-50' }}">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
    </div>
</div>
