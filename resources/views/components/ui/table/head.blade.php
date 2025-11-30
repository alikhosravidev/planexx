@props([
    'sticky' => false,
])

<thead {{ $attributes->class([
    'bg-bg-secondary border-b border-border-light',
    'sticky top-0 z-10' => $sticky,
]) }}>
    <tr>
        {{ $slot }}
    </tr>
</thead>
