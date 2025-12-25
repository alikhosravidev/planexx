@props([
    'states' => [],
    'currentStateId' => null,
    'showLabels' => true,
])

@php
    $currentStateIndex = 0;
    foreach ($states as $index => $state) {
        if (($state['id'] ?? null) == $currentStateId) {
            $currentStateIndex = $index;
            break;
        }
    }
    $totalStates = count($states);
@endphp

@if(!empty($states))
<div class="px-6 py-4 bg-slate-50 border-b border-border-light">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-semibold text-text-primary flex items-center gap-2">
            <i class="fa-solid fa-diagram-project text-indigo-600"></i>
            مراحل فرایند
        </h3>
        <span class="text-xs text-text-muted">
            مرحله {{ $currentStateIndex + 1 }} از {{ $totalStates }}
        </span>
    </div>
    <div class="flex items-center gap-2 overflow-x-auto pb-2 pt-3 scrollbar-hide">
        @foreach($states as $index => $state)
            <div class="flex items-center gap-2 flex-shrink-0">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all
                        @if($index < $currentStateIndex)
                            bg-indigo-600 text-white
                        @elseif($index === $currentStateIndex)
                            bg-indigo-600 text-white ring-4 ring-indigo-200
                        @else
                            bg-gray-200 text-slate-500
                        @endif
                    ">
                        @if($index < $currentStateIndex)
                            <i class="fa-solid fa-check text-sm"></i>
                        @else
                            {{ $index + 1 }}
                        @endif
                    </div>
                    @if($showLabels)
                        <span class="text-xs text-center mt-2 whitespace-nowrap max-w-[80px] truncate
                            {{ $index === $currentStateIndex ? 'text-indigo-600 font-bold' : 'text-slate-500' }}
                        ">{{ $state['name'] ?? '' }}</span>
                    @endif
                </div>

                @if($index < $totalStates - 1)
                    <div class="w-12 h-1 rounded-full flex-shrink-0 {{ $showLabels ? 'mt-[-24px]' : '' }} {{ $index < $currentStateIndex ? 'bg-indigo-600' : 'bg-gray-200' }}"></div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endif
