@props([
    'filters' => [],
    'resetUrl' => null,
])

@php
    $filterCount = count($filters) + 1;

    $gridConfig = match($filterCount) {
        1 => [
            'grid' => 'lg:grid-cols-1',
            'button' => '',
        ],
        2 => [
            'grid' => 'lg:grid-cols-2 sm:grid-cols-1',
            'button' => 'lg:col-span-1 md:col-span-1 col-span-1',
        ],
        3 => [
            'grid' => 'lg:grid-cols-3',
            'button' => 'lg:col-span-1',
        ],
        4 => [
            'grid' => 'lg:grid-cols-3 xl:grid-cols-4',
            'button' => 'xl:col-span-1',
        ],
        5 => [
            'grid' => 'lg:grid-cols-3 xl:grid-cols-5',
            'button' => 'lg:col-span-2 xl:col-span-1',
        ],
        6 => [
            'grid' => 'lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-7',
            'button' => 'lg:col-span-3 xl:col-span-4 2xl:col-span-1',
        ],
        default => [
            'grid' => 'lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5',
            'button' => match(($filterCount + 1) % 3) {
                1 => 'lg:col-span-3 xl:col-span-1',
                2 => 'lg:col-span-2 xl:col-span-1',
                default => 'xl:col-span-1',
            },
        ],
    };

    $gridClass = $gridConfig['grid'];
    $buttonClass = $gridConfig['button'];
@endphp

<div
    class="bg-bg-primary border border-border-light rounded-2xl p-4 sm:p-6"
    {{ $attributes }}
>
    <form>
        <div class="grid grid-cols-1 sm:grid-cols-2 {{ $gridClass }} gap-3 sm:gap-4">

            {{-- فیلترها --}}
            @foreach($filters as $filter)
                <div class="w-full min-w-0">
                    @switch($filter['type'])
                        @case('text')
                            <x-panel::forms.input
                                :name="$filter['name']"
                                :label="$filter['label']"
                                :value="$filter['value'] ?? ''"
                                :placeholder="$filter['placeholder'] ?? ''"
                            />
                            @break

                        @case('select')
                            <x-panel::forms.tom-select
                                :name="$filter['name']"
                                :label="$filter['label']"
                                :options="$filter['options']"
                                :placeholder="$filter['placeholder'] ?? ''"
                                :value="$filter['selected'] ?? ''"
                            />
                            @break

                        @case('tom-select-ajax')
                            <x-panel::forms.tom-select-ajax
                                :name="$filter['name']"
                                :label="$filter['label']"
                                :placeholder="$filter['placeholder'] ?? ''"
                                :url="$filter['url']"
                                :preload="true"
                                :value="$filter['selected'] ?? ''"
                                :template="$filter['template'] ?? 'keyValList'"
                            />
                            @break
                    @endswitch
                </div>
            @endforeach

            <div class="w-full sm:col-span-2 {{ $buttonClass }} flex items-end">
                <div class="w-full grid grid-cols-2 gap-2 sm:gap-3">
                    <x-panel::ui.button
                        type="submit"
                        icon="fa-solid fa-search"
                        class="w-full gap-1"
                        size="sm"
                    >
                        <span>اعمال</span>
                    </x-panel::ui.button>

                    @if($resetUrl)
                        <a
                            href="{{ $resetUrl }}"
                            class="flex items-center justify-center gap-2 w-full bg-bg-secondary text-text-secondary border border-border-medium px-3 sm:px-5 py-3.5 rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-sm sm:text-base leading-normal whitespace-nowrap text-center"
                        >
                            <i class="fa-solid fa-rotate-right"></i>
                            <span style="font-size: 13px">پاک کردن</span>
                        </a>
                    @else
                        <x-panel::ui.button
                            type="reset"
                            variant="secondary"
                            icon="fa-solid fa-rotate-right"
                            class="w-full"
                        >
                            <span style="font-size: 13px">پاک کردن</span>
                        </x-panel::ui.button>
                    @endif
                </div>
            </div>

        </div>
    </form>
</div>
