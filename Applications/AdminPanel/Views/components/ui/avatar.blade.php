@props([
    'name' => '',
    'image' => null,
    'size' => 'md',
])

@php
$sizes = [
    'sm' => 'w-8 h-8 text-xs',
    'md' => 'w-10 h-10 text-sm',
    'lg' => 'w-12 h-12 text-base',
    'xl' => 'w-16 h-16 text-xl',
];
$sizeClass = $sizes[$size] ?? $sizes['md'];
$initial = mb_substr($name, 0, 1, 'UTF-8');
@endphp

<div class="{{ $sizeClass }} bg-gradient-to-br from-primary to-slate-700 rounded-full flex items-center justify-center {{ $attributes->get('class') }}">
    @if($image)
        <img src="{{ $image }}" alt="{{ $name }}" class="w-full h-full rounded-full object-cover">
    @else
        <span class="text-white font-bold">{{ $initial }}</span>
    @endif
</div>
