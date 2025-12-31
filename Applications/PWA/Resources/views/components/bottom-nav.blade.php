{{-- PWA Bottom Navigation Component --}}
@props([
    'currentTab' => 'home',
])

@php
    $navItems = [
        [
            'key' => 'home',
            'label' => 'خانه',
            'icon' => 'fa-home',
            'url' => route('pwa.dashboard'),
        ],
        [
            'key' => 'tasks',
            'label' => 'تاریخچه',
            'icon' => 'fa-clock-rotate-left',
            'url' => '#',
        ],
        [
            'key' => 'documents',
            'label' => 'گزارش‌ها',
            'icon' => 'fa-chart-pie',
            'url' => '#',
        ],
        [
            'key' => 'profile',
            'label' => 'پروفایل',
            'icon' => 'fa-user',
            'url' => route('pwa.profile'),
        ],
    ];
@endphp

<!-- Bottom Navigation -->
<nav class="fixed bottom-0 left-0 right-0 bg-white px-4 py-2 z-40 max-w-[480px] mx-auto shadow-[0_-8px_24px_rgba(0,0,0,0.08)]">
    <div class="flex items-center justify-around">
        @foreach($navItems as $item)
            <a href="{{ $item['url'] }}"
               class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl transition-all min-w-[64px]
                      {{ $currentTab === $item['key'] ? 'bg-slate-900 text-white' : 'text-slate-500 hover:bg-gray-100' }}">
                <i class="fa-solid {{ $item['icon'] }} text-lg"></i>
                <span class="text-xs font-medium">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</nav>
