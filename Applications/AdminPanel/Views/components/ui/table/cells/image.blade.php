@props([
    'value' => null,
    'item' => null,
    'options' => [],
])

@php
    $size = $options['size'] ?? 'md';
    $rounded = $options['rounded'] ?? 'lg';
    $fallback = $options['fallback'] ?? null;
    
    $sizes = [
        'sm' => 'w-8 h-8',
        'md' => 'w-12 h-12',
        'lg' => 'w-16 h-16',
        'xl' => 'w-20 h-20',
    ];
    
    $roundedClasses = [
        'none' => '',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        'full' => 'rounded-full',
    ];
@endphp

@if($value)
    <img 
        src="{{ $value }}" 
        alt=""
        class="{{ $sizes[$size] ?? $sizes['md'] }} {{ $roundedClasses[$rounded] ?? 'rounded-lg' }} object-cover"
    >
@elseif($fallback)
    <div class="{{ $sizes[$size] ?? $sizes['md'] }} {{ $roundedClasses[$rounded] ?? 'rounded-lg' }} bg-bg-secondary flex items-center justify-center">
        <i class="fa-solid {{ $fallback }} text-text-muted"></i>
    </div>
@else
    <span class="text-text-muted">-</span>
@endif
