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

    $sizeClasses = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-1 text-sm',
        'lg' => 'px-3 py-1.5 text-base',
    ];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['sm'];

    $color = $resolve('color', null);
    $isCustomColor = str_starts_with($color, '#');

    $customBgColor = null;
    if ($isCustomColor) {
        $hex = ltrim($color, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        if (strlen($hex) === 6) {
            [$r, $g, $b] = sscanf($hex, '%02x%02x%02x');
            $customBgColor = "rgba({$r}, {$g}, {$b}, 0.12)";
        }
    }
@endphp

@if($color)
    <span
        class="inline-flex items-center gap-1 rounded-lg font-medium {{ $sizeClass }} {{ ! $isCustomColor ? "bg-{$color}/20 text-{$color}" : '' }}"
        style="{{ $isCustomColor ? "color:{$color};" . ($customBgColor ? " background-color: {$customBgColor};" : '') : '' }}"
    >
        @if($icon)
            <i class="fa-solid {{ $icon }} {{ $iconClass ?? '' }}"></i>
        @endif
        {{ $label ?? '-' }}
    </span>
@else
    <x-panel::ui.badge :variant="$variant" :size="$size">
        @if($icon)
            <i class="fa-solid {{ $icon }} {{ $iconClass ?? '' }}"></i>
        @endif
        {{ $label ?? '-' }}
    </x-panel::ui.badge>
@endif
