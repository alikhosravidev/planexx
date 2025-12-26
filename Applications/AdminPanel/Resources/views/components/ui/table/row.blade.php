@props([
    'clickable' => false,
    'selected' => false,
    'href' => null,
])

<tr {{ $attributes->class([
    'transition-colors duration-200',
    'hover:bg-bg-secondary/70' => !$selected,
    'bg-primary/5 hover:bg-primary/10' => $selected,
    'cursor-pointer' => $clickable || $href,
]) }}
    @if($href) onclick="window.location='{{ $href }}'" @endif
>
    {{ $slot }}
</tr>
