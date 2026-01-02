@props([
    'items',
    'cols' => 'grid-cols-1 grid-cols-2 lg:grid-cols-4',
])

<div class="grid {{ $cols }} gap-4 lg:gap-6 mb-8">
    @foreach ($items as $item)
        @php
            $title = $item['title'] ?? '';
            $value = $item['value'] ?? '';
            $icon = $item['icon'] ?? 'fa-solid fa-chart-line';
            $color = $item['color'] ?? 'blue';

            $iconColors = [
                'blue' => 'text-blue-500',
                'green' => 'text-green-500',
                'purple' => 'text-purple-500',
                'orange' => 'text-orange-500',
            ];
            $iconColor = $iconColors[$color] ?? $iconColors['blue'];
        @endphp

        <div class="bg-bg-primary border border-border-light rounded-2xl p-6 hover:shadow-md hover:border-{{ $color }}-200 transition-all duration-200 relative overflow-hidden group">
            <div class="absolute left-3 top-1/2 -translate-y-1/2 opacity-[0.05] group-hover:opacity-[0.12] transition-opacity duration-300">
                <i class="{{ $icon }} text-6xl md:text-7xl lg:text-[108px] {{ $iconColor }}"></i>
            </div>
            <div class="relative z-10">
                <div class="text-sm text-text-secondary mb-2 font-medium leading-normal">{{ $title }}</div>
                <div class="text-4xl font-bold text-text-primary leading-tight">{{ $value }}</div>
            </div>
        </div>
    @endforeach
</div>
