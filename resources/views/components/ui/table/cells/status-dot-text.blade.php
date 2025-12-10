@props([
    'value' => null,
    'item' => null,
    'options' => [],
])

@php
    $isActiveKey = $options['status_key'] ?? 'is_active';
    $labelKey = $options['label_key'] ?? 'name';
    $titleKey = $options['title_key'] ?? 'slug';

    $isActive = ! empty(data_get($item, $isActiveKey));
    $name = data_get($item, $labelKey) ?? '';
    $title = $titleKey ? (data_get($item, $titleKey) ?? '') : '';
@endphp

<div class="flex items-center gap-2.5">
    <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $isActive ? 'bg-green-500' : 'bg-gray-300' }}" title="{{ $isActive ? 'فعال' : 'غیرفعال' }}"></span>
    <span class="text-sm font-medium text-text-primary leading-snug truncate" @if($title) title="{{ $title }}" @endif>
        {{ $name }}
    </span>
</div>
