@props([
    'value' => null,
    'item' => null,
    'options' => [],
])

@php
    $currentState = $item['current_state'] ?? [];
    $stateName = $currentState['name'] ?? '-';
    $stateColor = $currentState['color'] ?? '#E3F2FD';
    $currentOrder = $item['current_state_order'] ?? 1;
    $totalStates = count($item['workflow']['states'] ?? []);
    $progress = $item['progress_percentage'] ?? 0;
    $priority = $item['priority'] ?? [];
    $isUrgent = ($priority['name'] ?? 'MEDIUM') === 'URGENT';
@endphp

<div class="w-48 flex-shrink-0">
    <div class="flex items-center gap-2 mb-2">
        <div class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $stateColor }}; filter: saturate(1.5) brightness(0.95);"></div>
        <span class="text-sm font-semibold text-text-primary truncate">{{ $stateName }}</span>
        <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-600">
            {{ $currentOrder }}/{{ $totalStates }}
        </span>
        @if($isUrgent)
            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-red-100 text-red-700 mr-auto">
                <i class="fa-solid fa-fire text-[10px]"></i> فوری
            </span>
        @endif
    </div>
    <div class="flex items-center gap-2">
        <div class="flex-1 h-2.5 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-all duration-300"
                 style="width: {{ $progress }}%; background-color: {{ $stateColor }}; filter: saturate(1.5) brightness(0.9);"></div>
        </div>
    </div>
</div>
