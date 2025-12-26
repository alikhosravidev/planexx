@props([
    'value' => null,
    'item' => null,
    'options' => [],
])

@php
    $remainingDays = $item['remaining_days'] ?? null;
    $isOverdue = $remainingDays !== null && $remainingDays < 0;
    $isToday = $remainingDays === 0;

    $formattedDate = '-';
    if ($value) {
        try {
            $formattedDate = $value['human']['short'];
        } catch (\Exception $e) {
            $formattedDate = $value;
        }
    }

    $bgClass = $isOverdue ? 'bg-red-50' : ($isToday ? 'bg-amber-50' : 'bg-gray-50');
    $iconClass = $isOverdue ? 'text-red-500' : ($isToday ? 'text-amber-500' : 'text-text-muted');
    $dateClass = $isOverdue ? 'text-red-600' : ($isToday ? 'text-amber-600' : 'text-text-primary');
    $remainingClass = $isOverdue ? 'text-red-500' : ($isToday ? 'text-amber-500' : 'text-text-muted');
@endphp

@if($value)
    <div class="flex items-center gap-2 px-3 py-2 rounded-lg {{ $bgClass }}">
        <i class="fa-solid fa-calendar-day {{ $iconClass }}"></i>
        <div class="text-left">
            <p class="text-xs font-medium {{ $dateClass }}" dir="ltr">
                {{ $formattedDate }}
            </p>
            @if($remainingDays !== null)
                <p class="text-[10px] {{ $remainingClass }}">
                    @if($isOverdue)
                        {{ abs($remainingDays) }} روز عقب‌افتاده
                    @elseif($isToday)
                        امروز
                    @else
                        {{ $remainingDays }} روز مانده
                    @endif
                </p>
            @endif
        </div>
    </div>
@else
    <span class="text-sm text-text-muted">-</span>
@endif
