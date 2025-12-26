{{--
    Task header with title, description, and tags
--}}

{{-- Task Title & Description --}}
<div class="p-6">
    <div class="flex items-start justify-between gap-4 mb-4">
        <div class="flex-1">
            {{-- Tags: Priority, Workflow, Department --}}
            <div class="flex items-center gap-3 mb-3">
                <span class="{{ $priorityStyle['bg'] }} {{ $priorityStyle['text'] }} px-3 py-1.5 rounded-lg text-xs font-semibold">
                    <i class="fa-solid fa-flag ml-1"></i>
                    {{ $priorityLabel }}
                </span>
                @if(isset($task['workflow']['name']))
                    <span class="bg-indigo-100 text-indigo-700 px-3 py-1.5 rounded-lg text-xs font-medium">
                        <i class="fa-solid fa-diagram-project ml-1"></i>
                        {{ $task['workflow']['name'] }}
                    </span>
                @endif
                @if(isset($task['workflow']['department']['name']))
                    <span class="bg-slate-100 text-slate-600 px-3 py-1.5 rounded-lg text-xs font-medium">
                        <i class="fa-solid fa-building ml-1"></i>
                        {{ $task['workflow']['department']['name'] }}
                    </span>
                @endif
            </div>
            <h1 class="text-xl font-bold text-text-primary mb-3 leading-relaxed">{{ $task['title'] ?? '-' }}</h1>
            @if(!empty($task['description']))
                <p class="text-text-secondary text-base leading-relaxed">{{ $task['description']['full'] ?? $task['description'] }}</p>
            @endif
        </div>
    </div>
</div>
