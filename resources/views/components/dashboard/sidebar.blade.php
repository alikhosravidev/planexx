@props([
    'name',
    'currentPage' => null,
    'headerVariant' => 'brand', // 'brand' or 'back'
    'brandTitle' => config('app.name'),
    'brandSubtitle' => 'سیستم یکپارچه درون سازمانی',
    'brandIcon' => 'fa-solid fa-network-wired',
    'backHref' => route('web.dashboard'),
    'backPrefix' => 'بازگشت به',
    'backTitle' => 'داشبورد اصلی',
    'backIcon' => 'fa-solid fa-arrow-right',
    'moduleTitle' => null,
    'moduleIcon' => 'fa-solid fa-sitemap',
    'color' => 'primary',
])

@php
    $menuItems = app('menu')->getTransformed($name);

    $colorConfig = [
        'primary' => [
            'gradient_from' => 'from-primary',
            'bg_solid' => 'bg-primary',
            'text' => 'text-primary',
            'hover_text' => 'hover:text-primary',
        ],
        'indigo' => [
            'gradient_from' => 'from-indigo-600',
            'bg_solid' => 'bg-indigo-600',
            'text' => 'text-indigo-600',
            'hover_text' => 'hover:text-indigo-600',
        ],
    ];

    $sidebarColor = $colorConfig[$color] ?? $colorConfig['primary'];
    $activeItemClasses = $sidebarColor['bg_solid'] . ' text-white shadow-sm';
@endphp

<aside class="hidden lg:flex flex-col w-[280px] bg-bg-primary border-l border-border-light h-screen sticky top-0">
    <div class="px-6 py-6 border-b border-border-light">
        @if ($headerVariant === 'back')
            <a href="{{ $backHref }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                <div class="w-10 h-10 bg-gradient-to-br {{ $sidebarColor['gradient_from'] }} to-slate-700 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="{{ $backIcon }} text-white text-lg"></i>
                </div>

                <div class="min-w-0">
                    <p class="text-xs text-text-muted leading-tight">{{ $backPrefix }}</p>
                    <h1 class="text-base font-bold text-text-primary leading-tight truncate">{{ $backTitle }}</h1>
                </div>
            </a>
        @else
            <a href="{{ route('web.dashboard') }}" class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 bg-gradient-to-br {{ $sidebarColor['gradient_from'] }} to-slate-700 rounded-lg flex items-center justify-center">
                    <i class="{{ $brandIcon }} text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-text-primary leading-tight">{{ $brandTitle }}</h1>
                    <p class="text-sm text-text-muted leading-tight">{{ $brandSubtitle }}</p>
                </div>
            </a>
        @endif
    </div>

    @if ($moduleTitle)
        <div class="px-6 py-4 bg-blue-50 border-b border-border-light">
            <div class="flex items-center gap-2.5">
                <i class="{{ $moduleIcon }} {{ $sidebarColor['text'] }} text-xl flex-shrink-0"></i>
                <h2 class="text-base font-bold text-text-primary leading-normal">{{ $moduleTitle }}</h2>
            </div>
        </div>
    @endif

    <nav class="flex-1 overflow-y-auto px-3 py-4">
        <div class="space-y-1">
            @foreach ($menuItems as $item)
                <a href="{{ $item['url'] }}"
                    @class([
                        'flex items-center gap-3 px-4 py-3 rounded-lg text-base font-medium transition-all duration-200',
                        $activeItemClasses => $currentPage === $item['id'],
                        'text-text-secondary hover:bg-bg-secondary hover:text-text-primary' => $currentPage !== $item['id'],
                    ])>
                    <i class="{{ $item['icon'] }} w-5 text-center text-lg"></i>
                    <span class="leading-normal">{{ $item['title'] }}</span>
                    @if ($currentPage === $item['id'])
                        <div class="mr-auto w-1.5 h-1.5 bg-white rounded-full"></div>
                    @endif
                </a>
            @endforeach
        </div>
    </nav>
    <div class="px-6 py-4 border-t border-border-light">
        <div class="text-xs text-text-muted text-center leading-relaxed">
            نسخه 1.0.0
        </div>
    </div>
</aside>

<button
    data-mobile-sidebar-toggle
    class="lg:hidden fixed bottom-6 left-6 w-14 h-14 {{ $sidebarColor['bg_solid'] }} text-white rounded-full shadow-lg flex items-center justify-center z-40 hover:scale-110 transition-transform duration-200">
    <i class="fa-solid fa-bars text-xl"></i>
</button>

<div
    data-mobile-sidebar-overlay
    class="lg:hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-40 hidden">
</div>

<aside
    data-mobile-sidebar
    class="lg:hidden fixed top-0 right-0 w-[280px] h-screen bg-bg-primary shadow-2xl z-50 transform translate-x-full transition-transform duration-300">
    <div class="px-6 py-6 border-b border-border-light flex items-center justify-between">
        @if ($headerVariant === 'back')
            <a href="{{ $backHref }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                <div class="w-10 h-10 bg-gradient-to-br from-primary to-slate-700 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="{{ $backIcon }} text-white text-lg"></i>
                </div>

                <div class="min-w-0">
                    <p class="text-[11px] text-text-muted leading-tight">{{ $backPrefix }}</p>
                    <h1 class="text-sm font-bold text-text-primary leading-tight truncate">{{ $backTitle }}</h1>
                </div>
            </a>
        @else
            <a href="{{ route('web.dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br {{ $sidebarColor['gradient_from'] }} to-slate-700 rounded-lg flex items-center justify-center">
                    <i class="{{ $brandIcon }} text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-text-primary leading-tight">{{ $brandTitle }}</h1>
                    <p class="text-xs text-text-muted leading-tight">{{ $brandSubtitle }}</p>
                </div>
            </a>
        @endif

        <button data-mobile-sidebar-close class="w-8 h-8 flex items-center justify-center text-text-muted {{ $sidebarColor['hover_text'] }} hover:bg-bg-secondary rounded transition-all duration-200">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>

    @if ($moduleTitle)
        <div class="px-6 py-4 bg-blue-50 border-b border-border-light">
            <div class="flex items-center gap-2.5">
                <i class="{{ $moduleIcon }} {{ $sidebarColor['text'] }} text-lg flex-shrink-0"></i>
                <h2 class="text-sm font-bold text-text-primary leading-normal">{{ $moduleTitle }}</h2>
            </div>
        </div>
    @endif

    <nav class="overflow-y-auto px-3 py-4" style="height: calc(100vh - 150px);">
        <div class="space-y-1">
            @foreach ($menuItems as $item)
                <a href="{{ $item['url'] }}"
                    @class([
                        'flex items-center gap-3 px-4 py-3 rounded-lg text-base font-medium transition-all duration-200',
                        'bg-primary text-white shadow-sm' => $currentPage === $item['id'],
                        'text-text-secondary hover:bg-bg-secondary hover:text-text-primary' => $currentPage !== $item['id'],
                    ])>
                    <i class="{{ $item['icon'] }} w-5 text-center text-lg"></i>
                    <span class="leading-normal">{{ $item['title'] }}</span>
                </a>
            @endforeach
        </div>
    </nav>
    <div class="absolute bottom-0 left-0 right-0 px-6 py-4 border-t border-border-light bg-bg-primary">
        <div class="text-xs text-text-muted text-center leading-relaxed">
            نسخه 1.0.0
        </div>
    </div>
</aside>
