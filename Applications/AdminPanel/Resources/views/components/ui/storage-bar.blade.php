@props([
    'used' => 0,
    'total' => 100,
    'unit' => 'GB',
])

@php
$percentage = $total > 0 ? ($used / $total) * 100 : 0;
@endphp

<div {{ $attributes }}>
    <div class="mb-1.5 flex items-center justify-between">
        <span class="text-[10px] text-text-muted">فضا</span>
        <span class="text-[10px] font-medium text-text-secondary">{{ $used }} / {{ $total }} {{ $unit }}</span>
    </div>
    <div class="h-1.5 bg-border-light rounded-full overflow-hidden">
        <div class="h-full bg-gradient-to-l from-primary to-blue-500 rounded-full" style="width: {{ $percentage }}%"></div>
    </div>
</div>
