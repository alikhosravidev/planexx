@props([
    'value' => null,
    'item' => null,
    'options' => [],
])

@php
    $states = $item['states'] ?? [];
    $regularStates = array_values(array_filter($states, fn ($s) => empty($s['is_final'])));
    $finalStates = array_values(array_filter($states, fn ($s) => ! empty($s['is_final'])));
@endphp

@if(! empty($states))
    <div class="flex items-center justify-end">
        <div class="inline-flex items-stretch flex-row-reverse">
            @if(! empty($finalStates))
                @php $finalCount = count($finalStates); @endphp
                <div class="relative flex-shrink-0 state-arrow" style="margin-right: -18px;">
                    <div data-dropdown-toggle="final-states-dropdown" class="relative h-[40px] min-w-[80px] flex flex-col items-center justify-center px-3 cursor-pointer" style="z-index: 30;">
                        <svg class="absolute inset-0 w-full h-full" viewBox="0 0 100 50" preserveAspectRatio="none">
                            <path d="M8,0 C4,0 0,4 0,8 L0,42 C0,46 4,50 8,50 L80,50 L92,25 L80,0 Z" fill="#E8F5E9" />
                        </svg>
                        <div class="relative z-10 text-center mr-1">
                            <div class="text-[11px] font-medium text-gray-700 leading-tight flex items-center gap-1">
                                <span>پایان</span>
                                <i class="fa-solid fa-chevron-down text-[10px] transition-transform"></i>
                            </div>
                            <div class="text-[10px] text-gray-500 leading-none mt-0.5">{{ $finalCount }} حالت</div>
                        </div>
                    </div>
                    <div id="final-states-dropdown" class="fixed bg-white border border-border-light rounded-lg shadow-lg z-50 min-w-[140px] py-1 hidden" style="pointer-events: auto;" data-dropdown>
                        @foreach($finalStates as $state)
                            <div class="px-3 py-2 flex items-center gap-2 text-sm hover:bg-gray-50 cursor-pointer">
                                <i class="fa-solid fa-check-circle text-xs" style="color: {{ $state['color'] }}"></i>
                                <span class="text-text-primary">{{ $state['name'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @php
                $stateIndex = 0;
                $totalRegular = count($regularStates);
            @endphp

            @foreach(array_reverse($regularStates) as $state)
                @php
                    $isLast = $stateIndex === $totalRegular - 1;
                    $taskCount = $state['tasks_count'] ?? 0;
                    $color = $state['color'] ?? '#E3F2FD';
                    $needsMargin = ! $isLast;
                @endphp
                <div class="relative flex-shrink-0 state-arrow" style="{{ $needsMargin ? 'margin-right: -18px;' : '' }}">
                    <div class="relative h-[40px] min-w-[80px] flex flex-col items-center justify-center px-3">
                        <svg class="absolute inset-0 w-full h-full" viewBox="0 0 100 50" preserveAspectRatio="none">
                            @if($isLast)
                                <path d="M92,0 L20,0 L8,25 L20,50 L92,50 C96,50 100,46 100,42 L100,8 C100,4 96,0 92,0 Z" fill="{{ $color }}" />
                            @else
                                <path d="M100,0 L20,0 L8,25 L20,50 L100,50 L88,25 Z" fill="{{ $color }}" />
                            @endif
                        </svg>
                        <div class="relative z-10 text-center">
                            <div class="text-[11px] font-medium text-text-primary leading-tight truncate max-w-[70px]">{{ $state['name'] ?? '' }}</div>
                            <div class="text-[12px] font-bold text-text-primary leading-none mt-0.5">{{ $taskCount }}</div>
                        </div>
                    </div>
                </div>
                @php $stateIndex++; @endphp
            @endforeach
        </div>
    </div>
@else
    <span class="text-xs text-text-muted leading-normal">بدون مرحله</span>
@endif
