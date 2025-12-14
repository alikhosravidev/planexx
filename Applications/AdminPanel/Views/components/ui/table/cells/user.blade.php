@props([
    'value' => null,
    'item' => null,
    'options' => [],
])

@php
    $name = $value ?? $item['name'] ?? 'کاربر';
    $image = $options['image_key'] ? data_get($item, $options['image_key']) : ($item['avatar'] ?? null);
    $subtitle = isset($options['subtitle_key']) ? data_get($item, $options['subtitle_key']) : null;
    $size = $options['size'] ?? 'md';
@endphp

<div class="flex items-center gap-3">
    <x-panel::ui.avatar
        :name="$name"
        :image="$image"
        :size="$size"
    />
    <div class="min-w-0">
        <p class="text-base text-text-primary font-medium leading-normal truncate">
            {{ $name }}
        </p>
        @if($subtitle)
            <p class="text-sm text-text-muted truncate">{{ $subtitle }}</p>
        @endif
    </div>
</div>
