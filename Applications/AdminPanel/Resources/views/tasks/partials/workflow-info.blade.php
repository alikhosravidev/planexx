{{--
    Workflow information section
--}}

@if(isset($task['workflow']))
    <div class="bg-bg-primary border border-border-light rounded-2xl p-5">
        <h3 class="text-base font-bold text-text-primary mb-4 flex items-center gap-2">
            <i class="fa-solid fa-diagram-project text-slate-600"></i>
            اطلاعات فرایند
        </h3>

        <div class="space-y-3">
            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                <span class="text-text-muted text-sm">نام فرایند</span>
                <span class="text-text-primary text-sm font-medium">{{ $task['workflow']['name'] ?? '-' }}</span>
            </div>
            @if(isset($task['workflow']['department']['name']))
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-text-muted text-sm">دپارتمان</span>
                    <span class="text-text-primary text-sm font-medium">{{ $task['workflow']['department']['name'] }}</span>
                </div>
            @endif
            @if(isset($task['workflow']['owner']['full_name']))
                <div class="flex items-center justify-between py-2">
                    <span class="text-text-muted text-sm">مالک فرایند</span>
                    <span class="text-text-primary text-sm font-medium">{{ $task['workflow']['owner']['full_name'] }}</span>
                </div>
            @endif
        </div>
    </div>
@endif
