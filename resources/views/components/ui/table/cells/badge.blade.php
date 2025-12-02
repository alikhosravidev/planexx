@props([
    'value' => null,
    'item' => null,
    'options' => [],
])

@php
    $variant = $options['variant'] ?? 'secondary';
    $size = $options['size'] ?? 'sm';
    $icon = $options['icon'] ?? null;
    $iconClass = $options['icon_class'] ?? null;
    if (!$iconClass && isset($options['icon_color']) && is_string($options['icon_color'])) {
        $iconClass = 'text-' . $options['icon_color'] . '-700';
    }

    if (isset($options['variants']) && isset($options['variants'][$value])) {
        $variant = $options['variants'][$value];
    }
    
    $prefix = $options['prefix'] ?? '';
    $suffix = $options['suffix'] ?? '';
    $label = $options['labels'][$value] ?? null;
    if ($label === null) {
        $label = (string) ($value ?? '-');
        if ($prefix || $suffix) {
            $label = trim($prefix . ' ' . $label . ' ' . $suffix);
        }
    }
@endphp

<x-ui.badge :variant="$variant" :size="$size">
    @if($icon)
        <i class="{{ $icon }} {{ $iconClass ?? '' }}"></i>
    @endif
    {{ $label ?? '-' }}
</x-ui.badge>
