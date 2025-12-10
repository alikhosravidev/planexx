@props([
    'value' => null,
    'item' => null,
    'options' => [],
])

@php
    $department = $item['department'] ?? $value;

    $label = null;
    $color = null;

    if (is_array($department)) {
        $label = $department['name'] ?? null;
        $color = $department['color'] ?? null;
        $color = explode('-', $color)[0];
    } else {
        $label = $department;
    }

    if (! $color) {
        $color = $options['fallback_color'] ?? 'slate';
    }
@endphp

@if($label)
    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium leading-normal justify-center bg-{{ $color }}-50 text-{{ $color }}-700">
        {{ $label }}
    </span>
@else
    <span class="text-sm text-text-muted leading-normal">{{ $options['empty_label'] ?? '-' }}</span>
@endif
