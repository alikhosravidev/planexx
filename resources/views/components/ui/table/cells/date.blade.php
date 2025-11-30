@props([
    'value' => null,
    'item' => null,
    'options' => [],
])

@php
    $format = $options['format'] ?? 'j F Y';
    $jalali = $options['jalali'] ?? true;
    $withTime = $options['with_time'] ?? false;
    $fallback = $options['fallback'] ?? '-';
    
    $formatted = $fallback;
    
    if ($value) {
        try {
            if ($jalali) {
                $formatted = verta($value)->format($format);
                if ($withTime) {
                    $formatted .= ' - ' . verta($value)->format('H:i');
                }
            } else {
                $formatted = \Carbon\Carbon::parse($value)->format($format);
            }
        } catch (\Exception $e) {
            $formatted = $fallback;
        }
    }
@endphp

<span class="text-sm text-text-secondary" dir="ltr">
    {{ $formatted }}
</span>
