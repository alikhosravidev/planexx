@props(['title' => 'داشبورد', 'breadcrumbs' => null, 'description' => null, 'actions' => []])

<header class="bg-bg-primary border-b border-border-light sticky top-0 z-30">
    <div class="px-6 py-5">
        <div class="flex items-center justify-between gap-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold text-text-primary leading-tight mb-2">{{ $title }}</h1>
                @if($description)
                    <p class="text-sm text-text-secondary leading-normal mb-2">{{ $description }}</p>
                @endif

                @if($breadcrumbs)
                    <nav class="flex items-center gap-2 text-xs text-text-muted">
                        @foreach($breadcrumbs as $index => $crumb)
                            @if($index > 0)
                                <i class="fa-solid fa-chevron-left text-[10px]"></i>
                            @endif

                            @if(isset($crumb['url']) && $index < count($breadcrumbs) - 1)
                                <a href="{{ $crumb['url'] }}" class="hover:text-primary transition-colors leading-normal">
                                    {{ $crumb['label'] }}
                                </a>
                            @else
                                <span class="text-text-primary font-medium leading-normal">{{ $crumb['label'] }}</span>
                            @endif
                        @endforeach
                    </nav>
                @endif
            </div>

            @if(!empty($actions))
                <div class="hidden lg:flex items-center gap-3">
                    @foreach($actions as $button)
                        @php
                            $type = $button['type'] ?? 'secondary';
                            $map = [
                                'primary' => 'bg-primary text-white hover:bg-primary-dark',
                                'secondary' => 'bg-bg-secondary text-text-primary hover:bg-slate-200',
                                'outline' => 'border-2 border-border-light text-text-primary hover:border-primary hover:text-primary',
                            ];
                            $classes = $map[$type] ?? $map['secondary'];
                        @endphp
                        <a href="{{ $button['url'] ?? '#' }}"
                           @if(!empty($button['onclick'])) onclick="{{ $button['onclick'] }}; return false;" @endif
                           @if(!empty($button['data_attrs']))
                               @foreach($button['data_attrs'] as $attr => $value)
                                   {{ $attr }}="{{ $value }}"
                               @endforeach
                           @endif
                           class="{{ $classes }} px-5 py-2.5 rounded-lg font-medium text-sm transition-all duration-200 flex items-center gap-2 leading-normal shadow-sm hover:shadow">
                            @if(!empty($button['icon']))
                                <i class="{{ $button['icon'] }}"></i>
                            @endif
                            <span>{{ $button['label'] ?? '' }}</span>
                        </a>
                    @endforeach
                </div>
            @endif

            <x-panel::ui.user-menu class="flex-shrink-0" />
        </div>

        @if(!empty($actions))
            <div class="lg:hidden mt-4 flex items-center gap-2 overflow-x-auto pb-1">
                @foreach($actions as $button)
                    @php
                        $type = $button['type'] ?? 'secondary';
                        $map = [
                            'primary' => 'bg-primary text-white',
                            'secondary' => 'bg-bg-secondary text-text-primary',
                            'outline' => 'border border-border-light text-text-primary',
                        ];
                        $classes = $map[$type] ?? $map['secondary'];
                    @endphp
                    <a href="{{ $button['url'] ?? '#' }}"
                       @if(!empty($button['onclick'])) onclick="{{ $button['onclick'] }}; return false;" @endif
                       @if(!empty($button['data_attrs']))
                           @foreach($button['data_attrs'] as $attr => $value)
                               {{ $attr }}="{{ $value }}"
                           @endforeach
                       @endif
                       class="{{ $classes }} px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 flex items-center gap-2 leading-normal whitespace-nowrap">
                        @if(!empty($button['icon']))
                            <i class="{{ $button['icon'] }}"></i>
                        @endif
                        <span>{{ $button['label'] ?? '' }}</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</header>
