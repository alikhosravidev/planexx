@props([
    'modules' => [],
    'cols' => 'grid-cols-2 sm:grid-cols-3 lg:grid-cols-6',
])

<div class="grid {{ $cols }} gap-4 lg:gap-5">
    @foreach ($modules as $module)
        @php
            $iconColors = [
                'blue' => 'text-blue-600',
                'green' => 'text-green-600',
                'purple' => 'text-purple-600',
                'teal' => 'text-teal-600',
                'orange' => 'text-orange-600',
                'amber' => 'text-amber-600',
                'indigo' => 'text-indigo-600',
                'stone' => 'text-stone-600',
                'gray' => 'text-gray-400',
            ];
            $gradientColors = [
                'blue' => 'from-blue-300/60',
                'green' => 'from-green-300/60',
                'purple' => 'from-purple-300/60',
                'teal' => 'from-teal-300/60',
                'orange' => 'from-orange-300/60',
                'amber' => 'from-amber-300/60',
                'indigo' => 'from-indigo-300/60',
                'stone' => 'from-stone-400/50',
                'gray' => 'from-gray-300/60',
            ];
            $bgColors = [
                'blue' => 'bg-blue-50',
                'green' => 'bg-green-50',
                'purple' => 'bg-purple-50',
                'teal' => 'bg-teal-50',
                'orange' => 'bg-orange-50',
                'amber' => 'bg-amber-50',
                'indigo' => 'bg-indigo-50',
                'stone' => 'bg-stone-100',
                'gray' => 'bg-gray-50',
            ];

            $title = data_get($module, 'title');
            $icon = data_get($module, 'icon');
            $color = data_get($module, 'color', 'blue');
            $url = data_get($module, 'url', '#');
            $enabled = (bool) data_get($module, 'enabled', true);

            $iconColor = $iconColors[$color] ?? $iconColors['blue'];
            $gradientColor = $gradientColors[$color] ?? $gradientColors['blue'];
            $bgColor = $bgColors[$color] ?? $bgColors['blue'];
        @endphp

        @if (!$enabled)
            <div class="bg-bg-primary border border-border-light rounded-2xl p-6 opacity-50 cursor-not-allowed relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br {{ $gradientColor }} via-white/20 to-transparent opacity-60"></div>
                <div class="relative z-10 w-12 h-12 {{ $bgColor }} rounded-xl flex items-center justify-center mb-4">
                    <i class="{{ $icon }} text-xl {{ $iconColor }}"></i>
                </div>
                <h3 class="relative z-10 text-sm font-semibold text-text-primary mb-2 leading-snug">{{ $title }}</h3>
                <span class="relative z-10 inline-flex items-center gap-1.5 bg-yellow-50 text-yellow-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                    <i class="fa-solid fa-clock text-[10px]"></i>
                    به زودی
                </span>
            </div>
        @else
            <a href="{{ $url }}" class="bg-bg-primary border border-border-light rounded-2xl p-6 hover:border-{{ $color }}-300 hover:shadow-md hover:-translate-y-1 transition-all duration-200 group relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br {{ $gradientColor }} via-white/40 to-transparent opacity-70 group-hover:opacity-90 transition-opacity duration-200"></div>
                <div class="relative z-10 w-12 h-12 {{ $bgColor }} rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200">
                    <i class="{{ $icon }} text-xl {{ $iconColor }}"></i>
                </div>
                <h3 class="relative z-10 text-sm font-semibold text-text-primary leading-snug flex items-center gap-2">
                    {{ $title }}
                    <i class="fa-solid fa-arrow-left text-[10px] {{ $iconColor }} opacity-0 group-hover:opacity-100 transition-opacity duration-200"></i>
                </h3>
            </a>
        @endif
    @endforeach
</div>
