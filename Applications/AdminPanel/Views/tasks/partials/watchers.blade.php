{{--
    Watchers section
--}}

@if(!empty($task['watchers']))
    <div class="bg-bg-primary border border-border-light rounded-2xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-text-primary flex items-center gap-2">
                <i class="fa-solid fa-eye text-slate-600"></i>
                ناظران
            </h3>
            <button class="text-sm text-indigo-600 hover:text-indigo-700 font-medium transition-colors">
                <i class="fa-solid fa-plus ml-1"></i>
                افزودن
            </button>
        </div>
        <div class="space-y-2">
            @foreach($task['watchers'] as $watcher)
                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                    <x-panel::ui.avatar
                        :name="$watcher['user']['full_name'] ?? 'کاربر'"
                        :image="$watcher['user']['avatar']['file_url'] ?? null"
                        size="sm"
                    />
                    <div class="flex-1 min-w-0">
                        <p class="text-text-primary text-sm font-medium truncate">{{ $watcher['user']['full_name'] ?? '-' }}</p>
                        <p class="text-text-muted text-xs truncate">{{ $watcher['watch_reason'] ?? '' }}</p>
                    </div>
                    <span class="w-2 h-2 rounded-full {{ ($watcher['watch_status'] ?? '') === 'open' ? 'bg-green-500' : 'bg-slate-400' }}"></span>
                </div>
            @endforeach
        </div>
    </div>
@endif
