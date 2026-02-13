@props([
    'value' => null,
    'item' => null,
    'options' => [],
])

@php
    $resolve = function ($key, $default = null) use ($options, $item, $value) {
        $v = $options[$key] ?? $default;
        return ($v instanceof \Closure) ? $v($item, $value) : $v;
    };

    $icon = $resolve('icon', 'fa-solid fa-circle');
    $iconBg = $resolve('icon_bg', 'bg-slate-50');
    $iconColor = $resolve('icon_color', 'text-slate-600');
    $description = $resolve('description', null);
    $descriptionClass = $resolve('description_class', 'text-xs text-text-muted mt-0.5');
    $size = $resolve('size', 'md');

    $sizeClasses = [
        'sm' => 'w-8 h-8',
        'md' => 'w-9 h-9',
        'lg' => 'w-12 h-12',
    ];
    $iconSize = $sizeClasses[$size] ?? $sizeClasses['md'];

    $text = $value ?? '-';
@endphp

<div class="flex items-center gap-3">
    @if($icon)
        <div class="{{ $iconSize }} {{ $iconBg }} rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="{{ $icon }} {{ $iconColor }}"></i>
        </div>
    @endif
    <div class="min-w-0 flex-1">
        <span class="text-base text-text-primary font-medium leading-normal">{{ $text }}</span>
        @if($description)
            <p class="{{ $descriptionClass }}">{{ $description }}</p>
        @endif
    </div>
</div>
