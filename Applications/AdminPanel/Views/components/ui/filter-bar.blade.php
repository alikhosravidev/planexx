@props([
    'filters' => [],
    'resetUrl' => null,
])

<div class="bg-bg-primary border border-border-light rounded-2xl p-6" {{ $attributes }}>
    <div class="flex flex-wrap items-stretch gap-4">

        @foreach($filters as $filter)
            @if($filter['type'] === 'text')
                <div class="flex-1 min-w-[100px]">
                    <x-panel::forms.input
                        :name="$filter['name']"
                        :label="$filter['label']"
                        :value="$filter['value'] ?? ''"
                        :placeholder="$filter['placeholder'] ?? ''"
                        class="min-w-[100px]"
                    />
                </div>
            @elseif($filter['type'] === 'select')
                <div class="flex-1 min-w-[100px]">
                    <x-panel::forms.select
                        :name="$filter['name']"
                        :label="$filter['label']"
                        :options="$filter['options']"
                        :value="$filter['selected'] ?? ''"
                        class="min-w-[100px]"
                    />
                </div>
            @elseif($filter['type'] === 'department')
                <div class="flex-1 min-w-[100px]">
                    <x-panel::organization.department.select
                        :name="$filter['name']"
                        :label="$filter['label']"
                        :value="$filter['selected']"
                        class="min-w-[100px]"
                        :options="$filter['options']"
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
