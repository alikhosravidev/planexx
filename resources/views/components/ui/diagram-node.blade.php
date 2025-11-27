@props([
    'title',
    'manager' => null,
    'count' => null,
    'icon' => null,
    'cardClass' => ''
])

<div class="rounded-xl p-4 text-center shadow-md {{ $cardClass }}">
    @if($icon)
        <i class="{{ $icon }} text-2xl mb-2"></i>
    @endif
    <p class="text-base font-semibold leading-normal">{{ $title }}</p>
    @if(!is_null($manager))
        <p class="text-sm leading-normal opacity-90">{{ $manager }}</p>
    @endif
    @if(!is_null($count))
        <p class="text-xs leading-normal opacity-75 mt-1">{{ $count }} نفر</p>
    @endif
</div>
