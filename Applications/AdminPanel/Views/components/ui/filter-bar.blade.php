@props([
    'filters' => [],
    'resetUrl' => null,
    'size' => 100,
])

<div class="bg-bg-primary border border-border-light rounded-2xl p-6" {{ $attributes }}>
    <div class="flex flex-wrap items-stretch gap-4">

        @foreach($filters as $filter)
            @if($filter['type'] === 'text')
                <div class="flex-1 min-w-[{{ $size }}px]">
                    <x-panel::forms.input
                        :name="$filter['name']"
                        :label="$filter['label']"
                        :value="$filter['value'] ?? ''"
                        :placeholder="$filter['placeholder'] ?? ''"
                        class="min-w-[{{ $size }}px]"
                    />
                </div>
            @elseif($filter['type'] === 'select')
                <div class="flex-1 min-w-[{{ $size }}px]">
                    <x-panel::forms.tom-select
                        :name="$filter['name']"
                        :label="$filter['label']"
                        :options="$filter['options']"
                        :placeholder="$filter['placeholder'] ?? ''"
                        :value="$filter['selected'] ?? ''"
                        class="min-w-[{{ $size }}px]"
                    />
                </div>
            @elseif($filter['type'] === 'tom-select-ajax')
                <div class="flex-1 min-w-[{{ $size }}px]">
                    <x-panel::forms.tom-select-ajax
                        :name="$filter['name']"
                        :label="$filter['label']"
                        :placeholder="$filter['placeholder'] ?? ''"
                        :url="$filter['url']"
                        :value="$filter['selected'] ?? ''"
                        :template="$filter['template'] ?? 'keyValList'"
                        class="min-w-[{{ $size }}px]"
                    />
                </div>
            @endif
        @endforeach

        <div class="flex items-center gap-2">
            <x-panel::ui.button type="submit" icon="fa-solid fa-search">
                اعمال فیلتر
            </x-panel::ui.button>
            @if($resetUrl)
                <a href="{{ $resetUrl }}" class="bg-bg-secondary text-text-secondary border border-border-medium px-5 py-3.5 rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal whitespace-nowrap">
                    <i class="fa-solid fa-rotate-right ml-2"></i>
                    <span>پاک کردن</span>
                </a>
            @else
                <x-panel::ui.button type="reset" variant="secondary" icon="fa-solid fa-rotate-right">
                    پاک کردن
                </x-panel::ui.button>
            @endif
        </div>

    </div>
</div>
