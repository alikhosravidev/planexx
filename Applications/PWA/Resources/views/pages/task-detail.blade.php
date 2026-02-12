@php
    $currentTab = 'home';
    $pageTitle = 'جزئیات کار';

    $priority = $task['priority'] ?? [];
    $priorityName = $priority['name'] ?? 'MEDIUM';
    $priorityLabel = $priority['label'] ?? 'متوسط';

    $priorityStyles = [
        'LOW' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'label' => 'کم'],
        'MEDIUM' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'متوسط'],
        'HIGH' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'بالا'],
        'URGENT' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'فوری'],
    ];

    $priorityStyle = $priorityStyles[$priorityName] ?? $priorityStyles['MEDIUM'];

    $workflow = $task['workflow'] ?? [];
    $workflowStates = $workflow['states'] ?? [];
    $currentState = $task['current_state'] ?? [];
    $currentStateId = $currentState['id'] ?? null;

    $currentStateIndex = 0;
    foreach ($workflowStates as $index => $state) {
        if (($state['id'] ?? null) == $currentStateId) {
            $currentStateIndex = $index;
            break;
        }
    }
    $nextState = $workflowStates[$currentStateIndex + 1] ?? null;

    $attachments = $task['attachments'] ?? [];
    $followUps = $task['followUps'] ?? [];

    $followUpStyles = [
        'FOLLOW_UP' => ['icon' => 'fa-comment', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-200'],
        'STATE_TRANSITION' => ['icon' => 'fa-arrow-right-arrow-left', 'bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'border-purple-200'],
        'USER_ACTION' => ['icon' => 'fa-hand-pointer', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-200'],
        'WATCHER_REVIEW' => ['icon' => 'fa-eye', 'bg' => 'bg-green-50', 'text' => 'text-green-600', 'border' => 'border-green-200'],
        'REFER' => ['icon' => 'fa-share', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-200'],
    ];

    $creator = $task['creator'] ?? [];
    $assignee = $task['assignee'] ?? [];
@endphp

<x-pwa::layouts.app title="{{ $pageTitle }}" :current-tab="$currentTab" :show-header="false">
    <x-slot:customHeader>
        <!-- Header -->
        <div class="sticky top-0 z-40 bg-white shadow-sm">
            <div class="px-5 pt-6 pb-4">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('pwa.tasks.index') }}" class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center hover:bg-gray-200 transition-all">
                            <i class="fa-solid fa-arrow-right text-slate-600"></i>
                        </a>
                        <div>
                            <h1 class="text-slate-900 text-lg font-bold">جزئیات کار</h1>
                            <p class="text-slate-500 text-xs">{{ $task['slug'] ?? '' }}</p>
                        </div>
                    </div>
                    <button data-modal-open="taskInfoModal" class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center hover:bg-gray-200 transition-all">
                        <i class="fa-solid fa-circle-info text-slate-600"></i>
                    </button>
                </div>

                <!-- Priority Badge & Workflow -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="{{ $priorityStyle['bg'] }} {{ $priorityStyle['text'] }} px-3 py-1.5 rounded-lg text-xs font-semibold">
                            <i class="fa-solid fa-flag ml-1"></i>
                            {{ $priorityStyle['label'] }}
                        </span>
                        <span class="bg-slate-100 text-slate-600 px-3 py-1.5 rounded-lg text-xs font-medium">
                            <i class="fa-solid fa-diagram-project ml-1"></i>
                            {{ $workflow['name'] ?? '—' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Workflow Progress - Scrollable -->
            @if(!empty($workflowStates))
                <div class="px-5 pb-4">
                    <div id="workflowProgress" class="bg-slate-50 rounded-2xl p-3 overflow-x-auto scrollbar-hide">
                        <div class="flex items-center gap-2" style="min-width: max-content;">
                            @foreach($workflowStates as $index => $state)
                                <div class="flex items-center gap-2 flex-shrink-0" {{ $index === $currentStateIndex ? 'id=currentStep' : '' }}>
                                    <!-- Step Circle -->
                                    <div class="flex flex-col items-center">
                                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold transition-all
                                            @if($index < $currentStateIndex)
                                                bg-slate-900 text-white
                                            @elseif($index === $currentStateIndex)
                                                bg-slate-900 text-white ring-4 ring-slate-300
                                            @else
                                                bg-gray-200 text-slate-500
                                            @endif
                                        ">
                                            @if($index < $currentStateIndex)
                                                <i class="fa-solid fa-check text-xs"></i>
                                            @else
                                                {{ $index + 1 }}
                                            @endif
                                        </div>
                                        <span class="text-[10px] text-center mt-1.5 whitespace-nowrap max-w-[70px] truncate
                                            {{ $index === $currentStateIndex ? 'text-slate-900 font-bold' : 'text-slate-500' }}
                                        ">{{ $state['name'] ?? '' }}</span>
                                    </div>

                                    <!-- Connector Line -->
                                    @if($index < count($workflowStates) - 1)
                                        <div class="w-8 h-1 rounded-full flex-shrink-0 mt-[-18px] {{ $index < $currentStateIndex ? 'bg-slate-900' : 'bg-gray-200' }}"></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </x-slot:customHeader>

    <!-- Main Content -->
    <div class="px-5 py-4 space-y-4">

        <!-- Deadline Alert -->
        @if(!empty($task['due_date']))
            @php
                $remainingDays = $task['remaining_days'] ?? null;
                $isOverdue = $remainingDays !== null && $remainingDays < 0;
                $isUrgent = $remainingDays !== null && $remainingDays <= 2;
            @endphp
            <div class="bg-gradient-to-r {{ $isOverdue ? 'from-red-600 to-red-700' : ($isUrgent ? 'from-red-500 to-red-600' : 'from-blue-500 to-blue-600') }} rounded-2xl p-4 text-white">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fa-solid fa-clock text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-white/80 text-xs mb-0.5">مهلت تکمیل</p>
                        <p class="text-white font-bold">{{ $task['due_date']['human']['default'] ?? $task['due_date']['formatted'] ?? '—' }}</p>
                    </div>
                    <div class="text-left">
                        <span class="bg-white/20 backdrop-blur-sm px-3 py-1.5 rounded-lg text-xs font-semibold">
                            @if($isOverdue)
                                {{ abs($remainingDays) }} روز گذشته
                            @elseif($remainingDays === 0)
                                امروز
                            @else
                                {{ $remainingDays }} روز مانده
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Task Details -->
        <div class="bg-white rounded-2xl p-5 shadow-sm">
            <h2 class="text-slate-900 text-base font-bold mb-3 leading-relaxed">{{ $task['title'] ?? 'بدون عنوان' }}</h2>
            <p class="text-slate-600 text-sm leading-relaxed mb-4">{{ $task['description']['full'] ?? '' }}</p>

            <!-- Meta Info -->
            <div class="space-y-3 pt-4 border-t border-gray-100">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 text-xs">ایجاد کننده</span>
                    <div class="flex items-center gap-2">
                        @if(!empty($creator['avatar']['file_url']))
                            <img src="{{ $creator['avatar']['file_url'] }}" alt="" class="w-6 h-6 rounded-full">
                        @else
                            <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center">
                                <i class="fa-solid fa-user text-slate-500 text-xs"></i>
                            </div>
                        @endif
                        <span class="text-slate-900 text-sm font-medium">{{ $creator['full_name'] ?? '—' }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 text-xs">تاریخ ایجاد</span>
                    <span class="text-slate-900 text-sm font-medium">{{ $task['created_at']['human']['default'] ?? '—' }}</span>
                </div>
                @if(!empty($task['next_follow_up_date']))
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 text-xs">پیگیری بعدی</span>
                        <span class="text-slate-900 text-sm font-medium">{{ $task['next_follow_up_date']['human']['long'] ?? '—' }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Attachments -->
        @if(!empty($attachments))
            <div class="bg-white rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-slate-900 text-sm font-bold flex items-center gap-2">
                        <i class="fa-solid fa-paperclip text-slate-600"></i>
                        پیوست‌ها
                    </h3>
                    <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded-lg text-xs font-medium">
                        {{ count($attachments) }} فایل
                    </span>
                </div>
                <div class="space-y-2">
                    @foreach($attachments as $attachment)
                        @php
                            $fileTypeLabel = $attachment['file_type_label'] ?? 'file';
                            $typeStyles = [
                                'document' => ['bg' => 'bg-red-100', 'text' => 'text-red-600', 'icon' => 'fa-file-pdf'],
                                'excel' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'icon' => 'fa-file-excel'],
                                'word' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'icon' => 'fa-file-word'],
                                'image' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'icon' => 'fa-file-image'],
                                'video' => ['bg' => 'bg-pink-100', 'text' => 'text-pink-600', 'icon' => 'fa-file-video'],
                                'audio' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600', 'icon' => 'fa-file-audio'],
                                'archive' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600', 'icon' => 'fa-file-zipper'],
                            ];
                            $style = $typeStyles[$fileTypeLabel] ?? ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'icon' => 'fa-file'];
                        @endphp
                        <a href="{{ $attachment['url'] ?? '#' }}" target="_blank" class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all cursor-pointer group">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $style['bg'] }} {{ $style['text'] }}">
                                <i class="fa-solid {{ $style['icon'] }}"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-slate-900 text-sm font-medium truncate">{{ $attachment['title'] ?? $attachment['original_name'] ?? 'فایل' }}</h4>
                                <p class="text-slate-500 text-xs">{{ $attachment['file_size_human'] ?? $attachment['file_size'] ?? '' }}</p>
                            </div>
                            <i class="fa-solid fa-download text-slate-400 group-hover:text-indigo-600 transition-colors"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- History Timeline -->
        @if(!empty($followUps))
            <div class="bg-white rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-slate-900 text-sm font-bold flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-slate-600"></i>
                        تاریخچه اقدامات
                    </h3>
                    <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded-lg text-xs font-medium">
                        {{ count($followUps) }} رویداد
                    </span>
                </div>

                <div class="relative">
                    <!-- Timeline Line -->
                    <div class="absolute top-0 bottom-0 right-5 w-0.5 bg-gray-200"></div>

                    <!-- Timeline Items -->
                    <div class="space-y-4">
                        @foreach($followUps as $index => $followUp)
                            @php
                                $type = $followUp['type']['name'] ?? 'FOLLOW_UP';
                                $style = $followUpStyles[$type] ?? $followUpStyles['FOLLOW_UP'];
                                $followUpCreator = $followUp['creator'] ?? [];
                            @endphp
                            <div class="relative flex gap-4 pr-2">
                                <!-- Timeline Dot -->
                                <div class="w-10 h-10 rounded-xl {{ $style['bg'] }} flex items-center justify-center flex-shrink-0 z-10 border-2 {{ $style['border'] }}">
                                    <i class="fa-solid {{ $style['icon'] }} {{ $style['text'] }} text-sm"></i>
                                </div>

                                <!-- Content -->
                                <div class="flex-1 pb-4 {{ $index !== count($followUps) - 1 ? 'border-b border-gray-100' : '' }}">
                                    <div class="flex items-start justify-between gap-2 mb-1">
                                        @if(isset($followUp['previous_assignee']) && isset($followUp['new_assignee']) && $followUp['previous_assignee_id'] !== $followUp['new_assignee_id'])
                                            <div class="flex items-center gap-2 mb-2">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-slate-900 text-xs font-semibold">{{ $followUp['previous_assignee']['full_name'] ?? '—' }}</span>
                                                </div>
                                                <i class="fa-solid fa-arrow-left text-slate-400 text-xs"></i>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-slate-900 text-xs font-semibold">{{ $followUp['new_assignee']['full_name'] ?? '—' }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2">
                                                @if(!empty($followUpCreator['avatar']['file_url']))
                                                    <img src="{{ $followUpCreator['avatar']['file_url'] }}" alt="" class="w-5 h-5 rounded-full">
                                                @else
                                                    <div class="w-5 h-5 rounded-full bg-slate-200 flex items-center justify-center">
                                                        <i class="fa-solid fa-user text-slate-500 text-[8px]"></i>
                                                    </div>
                                                @endif
                                                <span class="text-slate-900 text-xs font-semibold">{{ $followUpCreator['full_name'] ?? '—' }}</span>
                                            </div>
                                        @endif
                                        <span class="text-slate-400 text-[10px]">{{ isset($followUp['created_at']) ? $followUp['created_at']['human']['short'] . ' - ' . $followUp['created_at']['hour'] . ':' . $followUp['created_at']['minute'] : '—' }}</span>
                                    </div>

                                    @if(isset($followUp['previous_state']) && isset($followUp['new_state']) && $followUp['previous_state_id'] !== $followUp['new_state_id'])
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-[10px] font-medium">{{ $followUp['previous_state']['name'] ?? $followUp['previous_state'] }}</span>
                                            <i class="fa-solid fa-arrow-left text-slate-400 text-[10px]"></i>
                                            <span class="bg-slate-900 text-white px-2 py-1 rounded text-[10px] font-medium">{{ $followUp['new_state']['name'] ?? $followUp['new_state'] }}</span>
                                        </div>
                                    @endif

                                    <div class="flex items-center gap-2">
                                        @if(!empty($followUp['content']))
                                            <p class="text-slate-600 text-xs leading-relaxed flex-1">{{ $followUp['content']['default'] ?? '' }}</p>
                                        @endif
                                        @if(!empty($followUp['attachments']))
                                            <div class="flex gap-1">
                                                @foreach($followUp['attachments'] as $attachment)
                                                    <a
                                                        href="{{ route('web.documents.files.download', ['id' => $attachment['id']]) }}"
                                                        download="download"
                                                        class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-gray-100 rounded-lg transition-all duration-200"
                                                        title="دانلود پیوست">
                                                        <i class="fa-solid fa-download text-xs"></i>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    </div>

    <!-- Floating Action Bar -->
    <div class="fixed bottom-0 left-0 right-0 z-50">
        <div class="max-w-[480px] mx-auto bg-white border-t border-gray-200 px-5 py-4 shadow-lg">
            <div class="flex items-center gap-3">
                <button data-modal-open="followUpModal" class="flex-1 h-12 bg-gray-100 hover:bg-gray-200 text-slate-700 rounded-xl font-medium transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    <i class="fa-solid fa-comment"></i>
                    ثبت یادداشت
                </button>
                <button {{ $nextState === null ? 'disabled' : '' }} data-modal-open="forwardModal"
                        class="flex-1 h-12 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-medium transition-all active:scale-[0.98] flex items-center justify-center gap-2 {{ $nextState === null ? 'opacity-60 cursor-not-allowed' : '' }}">
                    <i class="fa-solid fa-paper-plane"></i>
                    ارجاع به مرحله بعد
                </button>
            </div>
        </div>
    </div>

    <!-- Follow-up Modal -->
    <x-pwa::modals.follow-up-modal :task-id="$task['id'] ?? null" />

    <!-- Forward Modal -->
    <x-pwa::modals.forward-modal :task="$task ?? null" :current-state="$currentState" :next-state="$nextState" />

    <!-- Task Info Modal -->
    <x-pwa::modals.task-info-modal :task="$task" :workflow="$workflow" :priority-style="$priorityStyle" />


</x-pwa::layouts.app>
