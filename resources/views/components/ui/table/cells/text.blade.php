@props([
    'value' => null,
    'item' => null,
    'options' => [],
])

@php
    $muted = $options['muted'] ?? true;
    $dir = $options['dir'] ?? null;
    $size = $options['size'] ?? 'base';
    $fallback = $options['fallback'] ?? '-';
    $icon = $options['icon'] ?? null;
    $iconClass = $options['icon_class'] ?? null;
    if (!$iconClass && isset($options['icon_color']) && is_string($options['icon_color'])) {
        $iconClass = 'text-' . $options['icon_color'] . '-600';
    }
    
    // Normalize value into a safe string for Blade escaping
    $display = $value;
    if (is_array($display)) {
        $display = implode('، ', array_map(static fn($v) => is_scalar($v) || (is_object($v) && method_exists($v, '__toString')) ? (string) $v : (is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : $fallback), $display));
    } elseif ($display instanceof \Illuminate\Support\Collection) {
        $display = $display->map(static fn($v) => is_scalar($v) || (is_object($v) && method_exists($v, '__toString')) ? (string) $v : (is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : $fallback))->join('، ');
    } elseif (is_object($display)) {
        $display = method_exists($display, '__toString') ? (string) $display : $fallback;
    } elseif ($display === null || $display === '') {
        $display = $fallback;
    }
@endphp

@if($icon)
    <div class="flex items-center gap-2">
        <i class="{{ $icon }} {{ $iconClass ?? 'text-text-muted' }}"></i>
        <span @class([
            "text-{$size} leading-normal",
            'text-text-secondary' => $muted,
            'text-text-primary' => !$muted,
        ]) @if($dir) dir="{{ $dir }}" @endif>
            {{ $display }}
        </span>
    </div>
@else
    <span @class([
        "text-{$size} leading-normal",
        'text-text-secondary' => $muted,
        'text-text-primary' => !$muted,
    ]) @if($dir) dir="{{ $dir }}" @endif>
        {{ $display }}
    </span>
@endif
