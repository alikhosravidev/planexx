@php
    $type = $action['type'] ?? 'button';
    $icon = $action['icon'];
    $tooltip = $action['tooltip'] ?? null;
    $variant = $action['variant'] ?? 'default';
    $size = $action['size'] ?? 'md';
    $confirm = $action['confirm'] ?? null;

    $variants = [
        'default' => 'text-text-muted hover:text-primary hover:bg-primary/10',
        'primary' => 'text-primary hover:text-primary-dark hover:bg-primary/10',
        'danger' => 'text-text-muted hover:text-red-600 hover:bg-red-50',
        'warning' => 'text-text-muted hover:text-amber-600 hover:bg-amber-50',
        'success' => 'text-text-muted hover:text-green-600 hover:bg-green-50',
        'info' => 'text-text-muted hover:text-blue-600 hover:bg-blue-50',
    ];

    $sizes = [
        'sm' => 'w-7 h-7 text-xs',
        'md' => 'w-8 h-8 text-sm',
        'lg' => 'w-9 h-9 text-base',
    ];

    $baseClasses = 'inline-flex items-center justify-center rounded-lg transition-all duration-200';
    $variantClass = $variants[$variant] ?? $variants['default'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $classes = "{$baseClasses} {$variantClass} {$sizeClass}";

    $href = '#';
    if ($type === 'link') {
        if (isset($action['route']) && $action['route'] !== '#') {
            $routeParams = [];
            $params = $action['params'] ?? ['id'];
            foreach ((array) $params as $param) {
                $routeParams[] = data_get($item, $param);
            }
            $href = route($action['route'], $routeParams);
        } elseif (isset($action['url'])) {
            $href = is_callable($action['url']) ? $action['url']($item) : $action['url'];
        }
    }

    $onclick = '';
    if (isset($action['click'])) {
        $clickParams = $action['click_params'] ?? ['id'];
        $params = [];
        foreach ((array) $clickParams as $param) {
            $value = data_get($item, $param);
            $params[] = is_string($value) ? "'{$value}'" : $value;
        }
        $paramsString = implode(', ', $params);

        if ($confirm) {
            $onclick = "if(confirm('{$confirm}')) { {$action['click']}({$paramsString}) }";
        } else {
            $onclick = "{$action['click']}({$paramsString})";
        }
    }

    $alpineClick = $action['x-click'] ?? null;
    if ($alpineClick) {
        $onclick = $alpineClick;
    }

    $dataAttrs = $action['data_attrs'] ?? [];
    $renderedDataAttrs = '';
    foreach ($dataAttrs as $key => $value) {
        if (is_callable($value)) {
            $attrValue = $value($item);
        } else {
            $attrValue = $value;
        }
        $renderedDataAttrs .= ' ' . $key . '="' . htmlspecialchars($attrValue, ENT_QUOTES) . '"';
    }
@endphp

@if($type === 'link')
    <a
        href="{{ $href }}"
        class="{{ $classes }}"
        @if($tooltip) title="{{ $tooltip }}" @endif
        @if($action['target'] ?? false) target="{{ $action['target'] }}" @endif
        {!! $renderedDataAttrs !!}
    >
        <i class="fa-solid {{ $icon }}"></i>
    </a>
@else
    <button
        type="button"
        class="{{ $classes }}"
        @if($tooltip) title="{{ $tooltip }}" @endif
        @if($onclick) onclick="{{ $onclick }}" @endif
        @if($action['disabled'] ?? false) disabled @endif
        {!! $renderedDataAttrs !!}
    >
        <i class="fa-solid {{ $icon }}"></i>
    </button>
@endif
