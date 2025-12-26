@props([
    'title' => null,
    'subtitle' => null,
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'bg-white border border-border-light rounded-2xl shadow-sm']) }}>
    @if($title || $subtitle)
        <div class="px-6 py-4 border-b border-border-light">
            @if($title)
                <h3 class="text-lg font-bold text-text-primary">{{ $title }}</h3>
            @endif
            @if($subtitle)
                <p class="text-sm text-text-secondary mt-1">{{ $subtitle }}</p>
            @endif
        </div>
    @endif
    
    <div @class(['p-6' => $padding])>
        {{ $slot }}
    </div>
</div>
