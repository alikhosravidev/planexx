@props([
    'title' => 'اطلاعات',
    'items' => [], // [['label' => '...', 'value' => '...', 'colspan' => 1]]
    'accentClass' => 'border-primary/20',
    'columns' => 2,
])

<div class="bg-bg-primary border border-border-light rounded-2xl p-6">
    <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">{{ $title }}</h2>

    @php
        $gridCols = $columns >= 2 ? 'md:grid-cols-' . $columns : 'md:grid-cols-2';
    @endphp

    <div class="grid grid-cols-1 {{ $gridCols }} gap-4">
        @foreach($items as $item)
            @php
                $label = is_array($item) ? ($item['label'] ?? '') : '';
                $value = is_array($item) ? ($item['value'] ?? '-') : '-';
                $colspan = is_array($item) ? (int)($item['colspan'] ?? 1) : 1;
                $spanClass = $colspan > 1 ? 'md:col-span-' . min($colspan, $columns) : '';
            @endphp
            <div class="border-r-4 {{ $accentClass }} pr-4 {{ $spanClass }}">
                <p class="text-sm text-text-secondary mb-1 leading-normal">{{ $label }}</p>
                <p class="text-base text-text-primary font-medium leading-normal">{{ $value }}</p>
            </div>
        @endforeach
    </div>
</div>
