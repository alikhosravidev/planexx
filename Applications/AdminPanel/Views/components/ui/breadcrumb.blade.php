@props(['items' => []])

<nav class="flex items-center gap-2 text-xs text-text-muted">
    @foreach($items as $index => $item)
        @if($index > 0)
            <i class="fa-solid fa-chevron-left text-[10px]"></i>
        @endif
        
        @if(isset($item['url']) && $index < count($items) - 1)
            <a href="{{ $item['url'] }}" class="hover:text-primary transition-colors leading-normal">
                {{ $item['label'] }}
            </a>
        @else
            <span class="text-text-primary font-medium leading-normal">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
