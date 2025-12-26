{{--
    Task information section
--}}

<div class="bg-bg-primary border border-border-light rounded-2xl p-5">
    <h3 class="text-base font-bold text-text-primary mb-4 flex items-center gap-2">
        <i class="fa-solid fa-circle-info text-slate-600"></i>
        اطلاعات کار
    </h3>

    <div class="space-y-4">
        {{-- Assignee --}}
        @if(isset($task['assignee']))
            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                <x-panel::ui.avatar
                    :name="$task['assignee']['full_name'] ?? 'کاربر'"
                    :image="$task['assignee']['avatar']['file_url'] ?? null"
                    size="md"
                />
                <div class="flex-1 min-w-0">
                    <p class="text-text-muted text-xs mb-0.5">مسئول فعلی</p>
                    <p class="text-text-primary text-sm font-semibold truncate">{{ $task['assignee']['full_name'] ?? '-' }}</p>
                    @if(isset($task['assignee']['position']['name']))
                        <p class="text-text-muted text-xs">{{ $task['assignee']['position']['name'] }}</p>
                    @endif
                </div>
            </div>
        @endif

        {{-- Creator --}}
        @if(isset($task['creator']))
            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                <x-panel::ui.avatar
                    :name="$task['creator']['full_name'] ?? 'کاربر'"
                    :image="$task['creator']['avatar']['file_url'] ?? null"
                    size="md"
                />
                <div class="flex-1 min-w-0">
                    <p class="text-text-muted text-xs mb-0.5">ایجاد کننده</p>
                    <p class="text-text-primary text-sm font-semibold truncate">{{ $task['creator']['full_name'] ?? '-' }}</p>
                    @if(isset($task['creator']['position']['name']))
                        <p class="text-text-muted text-xs">{{ $task['creator']['position']['name'] }}</p>
                    @endif
                </div>
            </div>
        @endif

        {{-- Current State --}}
        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-layer-group text-indigo-600"></i>
            </div>
            <div class="flex-1">
                <p class="text-text-muted text-xs mb-0.5">مرحله فعلی</p>
                <p class="text-text-primary text-sm font-semibold">{{ $task['current_state']['name'] ?? '-' }}</p>
            </div>
        </div>

        {{-- Created Date --}}
        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-regular fa-calendar text-blue-600"></i>
            </div>
            <div class="flex-1">
                <p class="text-text-muted text-xs mb-0.5">تاریخ ایجاد</p>
                <p class="text-text-primary text-sm font-medium">{{ isset($task['created_at']) ? $task['created_at']['human']['short'] : '-' }}</p>
            </div>
        </div>

        {{-- Next Follow-up --}}
        @if(isset($task['next_follow_up_date']))
            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-bell text-amber-600"></i>
                </div>
                <div class="flex-1">
                    <p class="text-text-muted text-xs mb-0.5">پیگیری بعدی</p>
                    <p class="text-text-primary text-sm font-medium">{{ verta($task['next_follow_up_date'])->format('Y/m/d - H:i') }}</p>
                </div>
            </div>
        @endif

        {{-- Estimated Hours --}}
        @if(isset($task['estimated_hours']))
            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fa-regular fa-clock text-cyan-600"></i>
                </div>
                <div class="flex-1">
                    <p class="text-text-muted text-xs mb-0.5">زمان تخمینی</p>
                    <p class="text-text-primary text-sm font-medium">{{ $task['estimated_hours'] }} ساعت</p>
                </div>
            </div>
        @endif
    </div>
</div>
