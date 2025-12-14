@props([
    'striped' => false,
    'hoverable' => true,
    'bordered' => false,
    'rounded' => true,
    'compact' => false,
])

<div {{ $attributes->class([
    'bg-white overflow-hidden',
    'border border-border-light' => $bordered,
]) }}>
    <div class="overflow-x-auto">
        <table @class([
            'w-full',
            '[&_tbody_tr:nth-child(odd)]:bg-bg-secondary/50' => $striped,
        ])>
            {{ $slot }}
        </table>
    </div>
</div>
