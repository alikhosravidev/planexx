@props([
    'title' => 'عملیات سریع',
    'actions' => [], // [['label' => '...', 'icon' => 'fa-solid fa-key', 'variant' => 'default'|'danger', 'href' => '#']]
])

@php
    $variantClasses = function ($variant) {
        return match ($variant) {
            'danger' => 'w-full bg-red-50 hover:bg-red-100 text-red-600 px-4 py-3 rounded-lg font-medium transition-all duration-200 text-sm leading-normal flex items-center gap-2',
            default => 'w-full bg-bg-secondary hover:bg-gray-100 text-text-primary px-4 py-3 rounded-lg font-medium transition-all duration-200 text-sm leading-normal flex items-center gap-2',
        };
    };
@endphp

<div class="bg-bg-primary border border-border-light rounded-2xl p-6">
    <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">{{ $title }}</h2>
    <div class="space-y-3">
        @foreach($actions as $action)
            @php
                $label = $action['label'] ?? '';
                $icon = $action['icon'] ?? null;
                $variant = $action['variant'] ?? 'default';
                $href = $action['href'] ?? null;
                $classes = $variantClasses($variant);
            @endphp
            @if($href)
                <a href="{{ $href }}" class="{{ $classes }}">
                    @if($icon)
                        <i class="{{ $icon }}"></i>
                    @endif
                    <span>{{ $label }}</span>
                </a>
            @else
                <button type="button" class="{{ $classes }}">
                    @if($icon)
                        <i class="{{ $icon }}"></i>
                    @endif
                    <span>{{ $label }}</span>
                </button>
            @endif
        @endforeach
    </div>
</div>
