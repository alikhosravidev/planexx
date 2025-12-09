@props([
    'name' => 'icon',
    'icons' => null,
    'selected' => null,
    'size' => 8,
    'wrapperClass' => 'px-lg'
])

@php
    $icons = $icons ?? [
        'fa-tag', 'fa-tags', 'fa-star', 'fa-heart', 'fa-bookmark',
        'fa-bolt', 'fa-gem', 'fa-fire', 'fa-thumbs-up', 'fa-trophy',
        'fa-check', 'fa-circle', 'fa-square', 'fa-bell', 'fa-lightbulb',
        'fa-rocket', 'fa-leaf', 'fa-tree', 'fa-sun', 'fa-moon',
        'fa-user', 'fa-users', 'fa-graduation-cap', 'fa-briefcase', 'fa-chart-line',
        'fa-comments', 'fa-paper-plane', 'fa-calendar-days', 'fa-globe', 'fa-crown',
    ];
    $selected = $selected ?? $icons[0] ?? null;
@endphp

<div class="">
    <div class="flex flex-wrap gap-2 {{ $wrapperClass }}">
        @foreach($icons as $icon)
            <label class="cursor-pointer icon-option">
                <input type="radio" name="{{ $name }}" value="{{ $icon }}"
                       class="peer hidden" {{ $icon === $selected ? 'checked' : '' }}>
                <div
                    class="w-{{ $size }} h-{{ $size }} bg-bg-secondary rounded-lg border-2 border-transparent peer-checked:border-primary peer-checked:bg-primary/10 transition-all flex items-center justify-center hover:bg-gray-100">
                    <i class="fa-solid {{ $icon }} text-lg text-text-secondary peer-checked:text-primary"></i>
                </div>
            </label>
        @endforeach
    </div>
</div>
