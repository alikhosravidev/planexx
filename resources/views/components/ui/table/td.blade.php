@props([
    'align' => 'right',
    'nowrap' => false,
    'compact' => false,
])

@php
    $alignments = [
        'right' => 'text-right',
        'center' => 'text-center',
        'left' => 'text-left',
    ];
@endphp

<td {{ $attributes->class([
    $compact ? 'px-4 py-2' : 'px-6 py-4',
    $alignments[$align] ?? 'text-right',
    'whitespace-nowrap' => $nowrap,
]) }}>
    {{ $slot }}
</td>
