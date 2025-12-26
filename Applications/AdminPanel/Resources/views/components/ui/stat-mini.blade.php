@props([
    'icon' => 'fa-file',
    'iconColor' => 'blue',
    'value' => '0',
    'label' => '',
])

@php
$colorClasses = [
    'blue' => 'text-blue-500',
    'green' => 'text-green-500',
    'amber' => 'text-amber-500',
    'yellow' => 'text-yellow-500',
    'purple' => 'text-purple-500',
    'pink' => 'text-pink-500',
    'red' => 'text-red-500',
];
$iconColorClass = $colorClasses[$iconColor] ?? $colorClasses['blue'];
@endphp

<div class="flex items-center gap-2 px-3 py-2 bg-bg-secondary/50 rounded-lg" {{ $attributes }}>
    <i class="fa-solid {{ $icon }} {{ $iconColorClass }} text-xs"></i>
    <div>
        <span class="text-xs font-semibold text-text-primary persian-num">{{ $value }}</span>
        @if($label)
            <span class="text-[10px] text-text-muted block leading-tight">{{ $label }}</span>
        @endif
    </div>
</div>
