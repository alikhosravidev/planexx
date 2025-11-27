@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'loading' => false,
])

@php
$variants = [
    'primary' => 'bg-slate-900 text-white hover:bg-slate-800',
    'secondary' => 'bg-white text-slate-900 border border-slate-300 hover:bg-slate-50',
    'danger' => 'bg-red-600 text-white hover:bg-red-700',
    'outline' => 'bg-transparent border-2 border-slate-900 text-slate-900 hover:bg-slate-900 hover:text-white',
];

$sizes = [
    'sm' => 'px-3 py-2 text-sm',
    'md' => 'px-6 py-3 text-base',
    'lg' => 'px-8 py-4 text-lg',
];

$classes = $variants[$variant] . ' ' . $sizes[$size];
$iconPrefix = ($icon && (strpos($icon, 'fa-solid') === false && strpos($icon, 'fa-regular') === false && strpos($icon, 'fa-brands') === false)) ? 'fa-solid ' : '';
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => "inline-flex items-center justify-center gap-2 rounded-xl font-medium transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed $classes"]) }}
    @if($loading) disabled @endif
>
    @if($loading)
        <i class="fa-solid fa-spinner fa-spin"></i>
    @elseif($icon)
        <i class="{{ $iconPrefix }}{{ $icon }}"></i>
    @endif
    
    {{ $slot }}
</button>
