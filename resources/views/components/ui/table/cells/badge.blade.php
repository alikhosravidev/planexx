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

    $variant = $resolve('variant', 'secondary');
    $size = $resolve('size', 'sm');
    $icon = $resolve('icon', null);
    $iconClass = $resolve('icon_class', null);
    if (!$iconClass && isset($options['icon_color']) && is_string($options['icon_color'])) {
        $iconClass = 'text-' . $options['icon_color'] . '-800';
    }

    if (isset($options['variants']) && isset($options['variants'][$value])) {
        $variant = $options['variants'][$value];
    }

    $prefix = $resolve('prefix', '');
    $suffix = $resolve('suffix', '');
    $label = $options['labels'][$value] ?? $resolve('label', null);
    if ($label === null) {
        $label = (string) ($value ?? '-');
        if ($prefix || $suffix) {
            $label = trim($prefix . ' ' . $label . ' ' . $suffix);
        }
    }

    $color = $resolve('color', null);
    $sizeClasses = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-1 text-sm',
        'lg' => 'px-3 py-1.5 text-base',
    ];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['sm'];

    $textColor = null;
    if (is_string($color)) {
        $parts = explode('-', $color);
        $base = $parts[0] ?? null;
        if ($base) {
            $textColor = $base . '-800';
        }
    }
@endphp

@if($color)
    <span class="inline-flex items-center gap-1 rounded-lg font-medium {{ $sizeClass }} bg-{{ $color }}/20 {{ $textColor ? 'text-' . $textColor : '' }}">
        @if($icon)
            <i class="fa-solid {{ $icon }} {{ $iconClass ?? '' }}"></i>
        @endif
        {{ $label ?? '-' }}
    </span>
@else
    <x-ui.badge :variant="$variant" :size="$size">
        @if($icon)
            <i class="fa-solid {{ $icon }} {{ $iconClass ?? '' }}"></i>
        @endif
        {{ $label ?? '-' }}
    </x-ui.badge>
@endif
