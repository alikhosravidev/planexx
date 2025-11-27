@props([
    'label',
    'count' => null,
    'boxClass' => '',
    'labelClass' => '',
    'countClass' => ''
])

<div class="rounded-xl p-3 text-center {{ $boxClass }}">
    <p class="text-sm font-semibold leading-normal {{ $labelClass }}">{{ $label }}</p>
    @if(!is_null($count))
        <p class="text-xs leading-normal mt-1 {{ $countClass }}">{{ $count }} نفر</p>
    @endif
</div>
