@props([
    'name' => 'icon',
    'label' => 'آیکون',
    'selected' => null,
    'icons' => null,
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
@endphp

<div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
    <div class="flex">
        <div
            {{ $attributes->merge(['class' => 'bg-bg-label border-l border-border-light px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal']) }}>
            {{ $label }}
        </div>
        <x-forms.icon-radio-group
            :name="$name"
            :icons="$icons"
            :selected="$selected"
        />
    </div>
</div>
