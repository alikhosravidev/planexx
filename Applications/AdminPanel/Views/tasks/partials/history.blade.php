@if(!empty($task['follow_ups']))
    @php
        $followUpStyles = [
            'FOLLOW_UP' => ['icon' => 'fa-comment', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-200'],
            'STATE_TRANSITION' => ['icon' => 'fa-arrow-right-arrow-left', 'bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'border-purple-200'],
            'USER_ACTION' => ['icon' => 'fa-hand-pointer', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-200'],
            'WATCHER_REVIEW' => ['icon' => 'fa-eye', 'bg' => 'bg-green-50', 'text' => 'text-green-600', 'border' => 'border-green-200'],
            'REFER' => ['icon' => 'fa-share', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-200'],
        ];
    @endphp
    <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-base font-bold text-text-primary flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-slate-600"></i>
                تاریخچه اقدامات
            </h3>
            <span class="bg-slate-100 text-slate-600 px-3 py-1.5 rounded-lg text-xs font-medium">
                {{ count($task['followUps']) }} رویداد
            </span>
        </div>

        <div class="relative">
            {{-- Timeline Line --}}
            <div class="absolute top-0 bottom-0 right-6 w-0.5 bg-gray-200"></div>

            {{-- Timeline Items --}}
            <div class="space-y-5">
                @foreach($task['followUps'] as $index => $followUp)
                    @php
                        $type = $followUp['type']['name'] ?? 'FOLLOW_UP';
                        $style = $followUpStyles[$type] ?? $followUpStyles['FOLLOW_UP'];
                    @endphp
                    <div class="relative flex gap-4 pr-2">
                        {{-- Timeline Dot --}}
                        <div class="w-12 h-12 rounded-xl {{ $style['bg'] }} flex items-center justify-center flex-shrink-0 z-10 border-2 {{ $style['border'] }}">
                            <i class="fa-solid {{ $style['icon'] }} {{ $style['text'] }}"></i>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 pb-4 {{ $index !== count($task['followUps']) - 1 ? 'border-b border-gray-100' : '' }}">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div class="flex items-center gap-2">
                                    <x-panel::ui.avatar
                                        name="{{ $followUp['creator']['full_name'] ?? '' }}"
                                        image="{{ $followUp['creator']['avatar']['file_url'] ?? null }}"
                                        size="sm"
                                    />
                                    <span class="text-text-primary text-sm font-semibold">{{ $followUp['creator']['full_name'] ?? '-' }}</span>
                                </div>
                                <span class="text-text-muted text-xs">{{ isset($followUp) ? $followUp['created_at']['human']['short'] . ' - ' . $followUp['created_at']['hour'] . ':' . $followUp['created_at']['minute'] : '-' }}</span>
                            </div>

                            @if($type === 'STATE_TRANSITION' && (isset($followUp['previous_state']) || isset($followUp['new_state'])))
                                <div class="flex items-center gap-2 mb-2">
                                    @if(isset($followUp['previous_state']))
                                        <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded text-xs font-medium">{{ $followUp['previous_state']['name'] ?? $followUp['previous_state'] }}</span>
                                        <i class="fa-solid fa-arrow-left text-slate-400 text-xs"></i>
                                    @endif
                                    @if(isset($followUp['new_state']))
                                        <span class="bg-indigo-600 text-white px-2.5 py-1 rounded text-xs font-medium">{{ $followUp['new_state']['name'] ?? $followUp['new_state'] }}</span>
                                    @endif
                                </div>
                            @endif

                            <div class="flex items-center">
                                @if(!empty($followUp['content']))
                                    <p class="text-text-secondary text-sm leading-relaxed">{{ $followUp['content']['full'] }}</p>
                                @endif
                                <div class="mr-auto">
                                    @foreach($followUp['attachments'] as $attachment)
                                        <a
                                            href="{{ route('web.documents.files.download', ['id' => $attachment['id']]) }}"
                                            class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200"
                                            title="دانلود پیوست">
                                            <i class="fa-solid fa-download text-slate-400 group-hover:text-indigo-600 transition-colors"></i>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
