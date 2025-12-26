@props([
    'value' => null,
    'item' => null,
    'options' => [],
])

@php
    $isActive = (bool) $value;
    $activeText = $options['active_text'] ?? 'فعال';
    $inactiveText = $options['inactive_text'] ?? 'غیرفعال';
    $activeVariant = $options['active_variant'] ?? 'success';
    $inactiveVariant = $options['inactive_variant'] ?? 'danger';
@endphp

<x-panel::ui.badge
    :variant="$isActive ? $activeVariant : $inactiveVariant"
    size="sm"
>
    <i class="fa-solid fa-circle text-[6px]"></i>
    {{ $isActive ? $activeText : $inactiveText }}
</x-panel::ui.badge>
