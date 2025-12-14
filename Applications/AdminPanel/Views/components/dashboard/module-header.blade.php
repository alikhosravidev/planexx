@props([
    'pageTitle' => 'عنوان صفحه',
    'breadcrumbs' => [],
    'actionButtons' => [],
])

<header class="bg-bg-primary border-b border-border-light sticky top-0 z-30">
    <div class="px-6 py-5">
        <div class="flex items-center justify-between gap-6">
            
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold text-text-primary leading-tight mb-2">{{ $pageTitle }}</h1>
                
                @if(!empty($breadcrumbs))
                    <x-panel::ui.breadcrumb :items="$breadcrumbs" />
                @endif
            </div>
            
            @if(!empty($actionButtons))
            <div class="hidden lg:flex items-center gap-3">
                @foreach($actionButtons as $button)
                    @php
                    $btnType = $button['type'] ?? 'secondary';
                    $btnClasses = [
                        'primary' => 'bg-primary text-white hover:bg-primary-dark',
                        'secondary' => 'bg-bg-secondary text-text-primary hover:bg-slate-200',
                        'outline' => 'border-2 border-border-light text-text-primary hover:border-primary hover:text-primary',
                    ];
                    $classes = $btnClasses[$btnType] ?? $btnClasses['secondary'];
                    @endphp
                    <a 
                        href="{{ $button['url'] ?? '#' }}" 
                        class="{{ $classes }} px-5 py-2.5 rounded-lg font-medium text-sm transition-all duration-200 flex items-center gap-2 leading-normal shadow-sm hover:shadow">
                        @if(isset($button['icon']))
                            <i class="{{ $button['icon'] }}"></i>
                        @endif
                        <span>{{ $button['label'] }}</span>
                    </a>
                @endforeach
            </div>
            @endif
            
            <x-panel::ui.user-menu />
            
        </div>
        
        @if(!empty($actionButtons))
        <div class="lg:hidden mt-4 flex items-center gap-2 overflow-x-auto pb-1">
            @foreach($actionButtons as $button)
                @php
                $btnType = $button['type'] ?? 'secondary';
                $btnClasses = [
                    'primary' => 'bg-primary text-white',
                    'secondary' => 'bg-bg-secondary text-text-primary',
                    'outline' => 'border border-border-light text-text-primary',
                ];
                $classes = $btnClasses[$btnType] ?? $btnClasses['secondary'];
                @endphp
                <a 
                    href="{{ $button['url'] ?? '#' }}" 
                    class="{{ $classes }} px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 flex items-center gap-2 leading-normal whitespace-nowrap">
                    @if(isset($button['icon']))
                        <i class="{{ $button['icon'] }}"></i>
                    @endif
                    <span>{{ $button['label'] }}</span>
                </a>
            @endforeach
        </div>
        @endif
    </div>
</header>
