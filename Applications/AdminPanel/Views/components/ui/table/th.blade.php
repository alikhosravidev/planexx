@props([
    'align' => 'right',
    'width' => null,
    'sortable' => false,
    'sortKey' => null,
    'sorted' => null,
])

@php
    $alignments = [
        'right' => 'text-right',
        'center' => 'text-center',
        'left' => 'text-left',
    ];
@endphp

<th {{ $attributes->class([
    'px-6 py-4 text-sm font-semibold text-text-primary leading-normal whitespace-nowrap',
    $alignments[$align] ?? 'text-right',
]) }}
    @if($width) style="width: {{ $width }}" @endif
>
    @if($sortable)
        <button 
            type="button"
            class="inline-flex items-center gap-2 hover:text-primary transition-colors group"
            onclick="handleSort('{{ $sortKey }}')"
        >
            {{ $slot }}
            <span class="text-text-muted group-hover:text-primary">
                @if($sorted === 'asc')
                    <i class="fa-solid fa-sort-up text-primary"></i>
                @elseif($sorted === 'desc')
                    <i class="fa-solid fa-sort-down text-primary"></i>
                @else
                    <i class="fa-solid fa-sort text-xs opacity-50"></i>
                @endif
            </span>
        </button>
    @else
        {{ $slot }}
    @endif
</th>
