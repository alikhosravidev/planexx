@props([
    'value' => null,
    'item' => null,
    'options' => [],
])

@php
    $variant = $options['variant'] ?? 'secondary';
    $size = $options['size'] ?? 'sm';
    
    if (isset($options['variants']) && isset($options['variants'][$value])) {
        $variant = $options['variants'][$value];
    }
    
    $label = $options['labels'][$value] ?? $value;
@endphp

<x-ui.badge :variant="$variant" :size="$size">
    {{ $label ?? '-' }}
</x-ui.badge>
