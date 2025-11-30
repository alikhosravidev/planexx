@props([
    'value' => null,
    'item' => null,
    'options' => [],
])

@php
    $route = $options['route'] ?? null;
    $url = $options['url'] ?? null;
    $params = $options['params'] ?? ['id'];
    $target = $options['target'] ?? '_self';
    $icon = $options['icon'] ?? null;
    
    $href = '#';
    if ($route) {
        $routeParams = [];
        foreach ($params as $param) {
            $routeParams[] = data_get($item, $param);
        }
        $href = route($route, $routeParams);
    } elseif ($url) {
        $href = is_callable($url) ? $url($item) : $url;
    }
@endphp

<a 
    href="{{ $href }}" 
    target="{{ $target }}"
    class="text-primary hover:text-primary-dark hover:underline inline-flex items-center gap-1 transition-colors"
>
    @if($icon)
        <i class="fa-solid {{ $icon }} text-xs"></i>
    @endif
    {{ $value ?? '-' }}
</a>
