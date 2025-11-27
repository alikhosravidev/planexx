@props([
    'moduleTitle' => 'ماژول',
    'moduleIcon' => 'fa-cube',
    'moduleColor' => 'primary',
    'menuItems' => [],
    'currentPage' => '',
])

@php
$colorClasses = [
    'primary' => 'bg-primary/5 text-primary',
    'teal' => 'bg-teal-50 text-teal-600',
    'blue' => 'bg-blue-50 text-blue-600',
    'green' => 'bg-green-50 text-green-600',
];
$bgColor = $colorClasses[$moduleColor] ?? $colorClasses['primary'];
@endphp

<aside class="hidden lg:flex flex-col w-[280px] bg-bg-primary border-l border-border-light min-h-screen sticky top-0 self-start">
    
    <div class="px-6 py-6 border-b border-border-light">
        <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
            <div class="w-10 h-10 bg-gradient-to-br from-primary to-slate-700 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-arrow-right text-white text-lg"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-text-muted leading-tight">بازگشت به</p>
                <h1 class="text-base font-bold text-text-primary leading-tight truncate">داشبورد اصلی</h1>
            </div>
        </a>
    </div>
    
    <div class="px-6 py-4 {{ $bgColor }} border-b border-border-light">
        <div class="flex items-center gap-2.5">
            <i class="fa-solid {{ $moduleIcon }} text-xl flex-shrink-0"></i>
            <h2 class="text-base font-bold text-text-primary leading-normal">{{ $moduleTitle }}</h2>
        </div>
    </div>
    
    <nav class="flex-1 overflow-y-auto px-3 py-4">
        <div class="space-y-1">
            @foreach($menuItems as $item)
                @php
                $isActive = $currentPage === $item['id'];
                $baseClasses = 'flex items-center gap-3 px-4 py-3 rounded-lg text-base font-medium transition-all duration-200';
                $classes = $isActive 
                    ? $baseClasses . ' bg-' . $moduleColor . '-600 text-white shadow-sm'
                    : $baseClasses . ' text-text-secondary hover:bg-bg-secondary hover:text-text-primary';
                @endphp
                
                <a href="{{ $item['url'] }}" class="{{ $classes }}">
                    <i class="{{ $item['icon'] }} w-5 text-center text-lg"></i>
                    <span class="leading-normal">{{ $item['label'] }}</span>
                    @if($isActive)
                        <div class="mr-auto w-1.5 h-1.5 bg-white rounded-full"></div>
                    @endif
                </a>
            @endforeach
        </div>
    </nav>
    
    <div class="px-6 py-4 border-t border-border-light">
        <div class="text-xs text-text-muted text-center leading-relaxed">
            {{ $moduleTitle }}
        </div>
    </div>
    
</aside>

<button 
    data-mobile-sidebar-toggle
    class="lg:hidden fixed bottom-6 left-6 w-14 h-14 bg-{{ $moduleColor }}-600 text-white rounded-full shadow-lg flex items-center justify-center z-40 hover:scale-110 transition-transform duration-200">
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
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-{{ $moduleColor }}-600 to-{{ $moduleColor }}-700 rounded-lg flex items-center justify-center">
                <i class="fa-solid {{ $moduleIcon }} text-white text-lg"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-text-primary leading-tight">{{ $moduleTitle }}</h1>
            </div>
        </div>
        <button data-mobile-sidebar-close class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
    
    <nav class="overflow-y-auto px-3 py-4" style="height: calc(100vh - 150px);">
        <div class="space-y-1">
            <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-base font-medium text-text-secondary hover:bg-bg-secondary hover:text-text-primary transition-all duration-200 border-b border-border-light mb-2 pb-3">
                <i class="fa-solid fa-arrow-right w-5 text-center text-lg"></i>
                <span class="leading-normal">بازگشت به داشبورد</span>
            </a>
            
            @foreach($menuItems as $item)
                @php
                $isActive = $currentPage === $item['id'];
                $baseClasses = 'flex items-center gap-3 px-4 py-3 rounded-lg text-base font-medium transition-all duration-200';
                $classes = $isActive 
                    ? $baseClasses . ' bg-' . $moduleColor . '-600 text-white shadow-sm'
                    : $baseClasses . ' text-text-secondary hover:bg-bg-secondary hover:text-text-primary';
                @endphp
                
                <a href="{{ $item['url'] }}" class="{{ $classes }}">
                    <i class="{{ $item['icon'] }} w-5 text-center text-lg"></i>
                    <span class="leading-normal">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
    </nav>
    
    <div class="absolute bottom-0 left-0 right-0 px-6 py-4 border-t border-border-light bg-bg-primary">
        <div class="text-xs text-text-muted text-center leading-relaxed">
            {{ $moduleTitle }}
        </div>
    </div>
    
</aside>

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.querySelector('[data-mobile-sidebar-toggle]');
    const sidebar = document.querySelector('[data-mobile-sidebar]');
    const overlay = document.querySelector('[data-mobile-sidebar-overlay]');
    const closeBtn = document.querySelector('[data-mobile-sidebar-close]');
    
    function openSidebar() {
        sidebar.classList.remove('translate-x-full');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeSidebar() {
        sidebar.classList.add('translate-x-full');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
    }
    
    toggleBtn?.addEventListener('click', openSidebar);
    closeBtn?.addEventListener('click', closeSidebar);
    overlay?.addEventListener('click', closeSidebar);
});
</script>
@endpush
@endonce
