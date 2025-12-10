@props([
    'value' => null,
    'item' => null,
    'options' => [],
])

@php
    $ownerKey = $options['owner_key'] ?? 'owner';
    $nameKey = $options['name_key'] ?? 'full_name';

    $owner = data_get($item, $ownerKey);
    if (is_array($owner)) {
        $ownerName = $owner[$nameKey] ?? null;
    } else {
        $ownerName = $owner;
    }
@endphp

<span class="inline-flex items-center gap-1.5 text-sm text-text-secondary leading-normal" title="مدیر">
    <i class="fa-solid fa-user-tie text-xs"></i>
    <span class="truncate">{{ $ownerName ?? '-' }}</span>
</span>
