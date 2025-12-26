@if(isset($task['due_date']))
    @php
        $remainingDays = $task['remaining_days'] ?? null;
        $isOverdue = $remainingDays !== null && $remainingDays < 0;
        $isToday = $remainingDays === 0;
    @endphp
    <div class="bg-gradient-to-r {{ $priorityStyle['gradient'] ?? 'from-blue-500 to-blue-600' }} rounded-2xl p-5 text-white">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm flex-shrink-0">
                <i class="fa-solid fa-clock text-2xl"></i>
            </div>
            <div class="flex-1">
                <p class="text-white/80 text-sm mb-1">مهلت تکمیل کار</p>
                <p class="text-white text-lg font-bold">{{ $task['due_date']['human']['short'] }} ساعت {{ $task['due_date']['hour'] }}</p>
            </div>
            <div class="text-left flex-shrink-0">
                <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-xl text-sm font-semibold">
                    @if($isOverdue)
                        {{ abs($remainingDays) }} روز عقب‌افتاده
                    @elseif($isToday)
                        امروز
                    @elseif($remainingDays !== null)
                        {{ $remainingDays }} روز مانده
                    @endif
                </span>
            </div>
        </div>
    </div>
@endif
