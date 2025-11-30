@props([
    'value' => null,
    'item' => null,
    'options' => [],
])

@php
    $currency = $options['currency'] ?? 'تومان';
    $decimals = $options['decimals'] ?? 0;
    $fallback = $options['fallback'] ?? '-';
    $free_text = $options['free_text'] ?? 'رایگان';
    
    $formatted = $fallback;
    
    if ($value !== null) {
        if ($value == 0 && ($options['show_free'] ?? false)) {
            $formatted = $free_text;
        } else {
            $formatted = number_format($value, $decimals) . ' ' . $currency;
        }
    }
@endphp

<span class="text-base text-text-primary font-medium" dir="ltr">
    {{ $formatted }}
</span>
