@props([
    'task' => [],
    'workflow' => [],
    'priorityStyle' => [],
])

@php
    $creator = $task['creator'] ?? [];
    $assignee = $task['assignee'] ?? [];
    $watchers = $task['watchers'] ?? [];
    $department = $workflow['department'] ?? [];
    $owner = $workflow['owner'] ?? [];
@endphp

<!-- Task Info Modal -->
<div id="taskInfoModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div onclick="event.stopPropagation()" class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-[480px] max-h-[85vh] overflow-hidden shadow-2xl">

        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4">
            <div class="flex items-center justify-between">
                <h2 class="text-slate-900 text-lg font-bold flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-slate-600"></i>
                    اطلاعات تکمیلی کار
                </h2>
                <button onclick="closeTaskInfoModal()" class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all">
                    <i class="fa-solid fa-xmark text-slate-600"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="px-6 py-5 overflow-y-auto max-h-[calc(85vh-80px)]">

            <!-- Task ID -->
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl mb-3">
                <div class="w-10 h-10 bg-slate-900 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-hashtag text-white text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-slate-400 text-xs mb-0.5">شناسه کار</p>
                    <p class="text-slate-900 text-sm font-bold">{{ $task['slug'] ?? '—' }}</p>
                </div>
            </div>

            <!-- Creator -->
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl mb-3">
                @if(!empty($creator['avatar']['url']))
                    <img src="{{ $creator['avatar']['url'] }}" alt="" class="w-10 h-10 rounded-full">
                @else
                    <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center">
                        <i class="fa-solid fa-user text-slate-500"></i>
                    </div>
                @endif
                <div class="flex-1">
                    <p class="text-slate-400 text-xs mb-0.5">ایجاد کننده</p>
                    <p class="text-slate-900 text-sm font-semibold">{{ $creator['full_name'] ?? '—' }}</p>
                </div>
            </div>

            <!-- Created Date -->
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl mb-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fa-regular fa-calendar text-blue-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-slate-400 text-xs mb-0.5">تاریخ ایجاد</p>
                    <p class="text-slate-900 text-sm font-medium">{{ $task['created_at']['human']['full'] ?? '—' }}</p>
                </div>
            </div>

            <!-- Workflow -->
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl mb-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-diagram-project text-purple-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-slate-400 text-xs mb-0.5">گردش کار</p>
                    <p class="text-slate-900 text-sm font-medium">{{ $workflow['name'] ?? '—' }}</p>
                </div>
            </div>

            <!-- Department -->
            @if(!empty($department))
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl mb-3">
                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-building text-amber-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-slate-400 text-xs mb-0.5">دپارتمان</p>
                        <p class="text-slate-900 text-sm font-medium">{{ $department['name'] ?? '—' }}</p>
                    </div>
                </div>
            @endif

            <!-- Workflow Owner -->
            @if(!empty($owner))
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl mb-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-user-tie text-green-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-slate-400 text-xs mb-0.5">مالک فرایند</p>
                        <p class="text-slate-900 text-sm font-medium">{{ $owner['full_name'] ?? '—' }}</p>
                    </div>
                </div>
            @endif

            <!-- Priority -->
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl mb-3">
                <div class="w-10 h-10 {{ $priorityStyle['bg'] ?? 'bg-blue-100' }} rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-flag {{ $priorityStyle['text'] ?? 'text-blue-600' }} text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-slate-400 text-xs mb-0.5">اولویت</p>
                    <p class="text-slate-900 text-sm font-medium">{{ $priorityStyle['label'] ?? '—' }}</p>
                </div>
            </div>

            <!-- Estimated Hours -->
            @if(!empty($task['estimated_hours']))
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl mb-3">
                    <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center">
                        <i class="fa-regular fa-clock text-cyan-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-slate-400 text-xs mb-0.5">زمان تخمینی</p>
                        <p class="text-slate-900 text-sm font-medium">{{ $task['estimated_hours'] }} ساعت</p>
                    </div>
                </div>
            @endif

            <!-- Watchers Section -->
            @if(!empty($watchers))
                <div class="mt-5 pt-4 border-t border-gray-100">
                    <h4 class="text-slate-700 text-sm font-semibold mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-eye text-slate-500"></i>
                        ناظران کار
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($watchers as $watcher)
                            @php
                                $watcherUser = $watcher['user'] ?? $watcher;
                            @endphp
                            <div class="flex items-center gap-2 bg-slate-100 rounded-full pl-3 pr-1 py-1">
                                @if(!empty($watcherUser['avatar']['url']))
                                    <img src="{{ $watcherUser['avatar']['url'] }}" alt="" class="w-6 h-6 rounded-full">
                                @else
                                    <div class="w-6 h-6 rounded-full bg-slate-300 flex items-center justify-center">
                                        <i class="fa-solid fa-user text-slate-500 text-xs"></i>
                                    </div>
                                @endif
                                <span class="text-slate-700 text-xs font-medium">{{ $watcherUser['full_name'] ?? $watcherUser['name'] ?? '—' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

    </div>
</div>

<style>
    #taskInfoModal > div {
        animation: slideUp 0.3s ease-out;
    }
</style>
