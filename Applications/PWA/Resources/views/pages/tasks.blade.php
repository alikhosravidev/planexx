@php
    $currentTab = 'home';
    $pageTitle = 'وظایف من';

    $typeIcons = [
        'approval' => 'fa-stamp',
        'meeting'  => 'fa-users',
        'task'     => 'fa-file-lines',
    ];

    $counts = $stats ?? [
        'all' => $user['tasks_count'] ?? count($tasks),
        'pending' => $user['waiting_tasks'] ?? 0,
        'completed' => $user['completed_tasks'] ?? 0,
    ];
@endphp

<x-pwa::layouts.app title="{{ $pageTitle }}" :current-tab="$currentTab" :show-header="false">
    <x-slot:customHeader>
        <!-- Header -->
        <div class="sticky top-0 z-40 bg-white shadow-sm px-5 pt-6 pb-4">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <a href="{{ route('pwa.dashboard') }}" class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center hover:bg-gray-200 transition-all">
                        <i class="fa-solid fa-arrow-right text-slate-600"></i>
                    </a>
                    <h1 class="text-slate-900 text-xl font-bold">{{ $pageTitle }}</h1>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" data-modal-open="createTaskModal" class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center hover:bg-slate-800 transition-all">
                        <i class="fa-solid fa-plus text-white"></i>
                    </button>
                    <span class="bg-slate-900 text-white px-3 py-1.5 rounded-lg text-xs font-medium">
                        {{ $counts['pending'] }} در انتظار
                    </span>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="flex items-center gap-2">
                <a href="{{ route('pwa.tasks.index', ['status' => 'all']) }}"
                   class="flex-1 py-2.5 rounded-xl text-sm font-medium text-center transition-all {{ $activeFilter === 'all' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-slate-600 hover:bg-gray-200' }}">
                    همه ({{ $counts['all'] }})
                </a>
                <a href="{{ route('pwa.tasks.index', ['status' => 'pending']) }}"
                   class="flex-1 py-2.5 rounded-xl text-sm font-medium text-center transition-all {{ $activeFilter === 'pending' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-slate-600 hover:bg-gray-200' }}">
                    در انتظار ({{ $counts['pending'] }})
                </a>
                <a href="{{ route('pwa.tasks.index', ['status' => 'completed']) }}"
                   class="flex-1 py-2.5 rounded-xl text-sm font-medium text-center transition-all {{ $activeFilter === 'completed' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-slate-600 hover:bg-gray-200' }}">
                    انجام شده ({{ $counts['completed'] }})
                </a>
            </div>
        </div>
    </x-slot:customHeader>

    <!-- Main Content -->
    <div class="px-5 py-6">

        @if(empty($tasks))
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-16">
                <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fa-solid fa-check-circle text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-slate-900 text-base font-semibold mb-2">وظیفه‌ای یافت نشد</h3>
                <p class="text-slate-500 text-sm text-center">هیچ وظیفه‌ای در این دسته‌بندی وجود ندارد</p>
            </div>
        @else
            <!-- Tasks List -->
            <div class="space-y-3">
                @foreach($tasks as $task)
                    @php
                        $priorityName = $task['priority']['name'] ?? 'MEDIUM';
                        $isHighPriority = in_array($priorityName, ['HIGH', 'URGENT']);
                        $isCompleted = $task['is_completed'] ?? false;
                        $taskType = $task['type'] ?? 'task';
                        $typeIcon = $typeIcons[$taskType] ?? 'fa-file-lines';
                    @endphp
                    <a href="{{ route('pwa.tasks.show', $task['id']) }}"
                       class="block bg-white {{ $isHighPriority && !$isCompleted ? 'border-r-4 border-r-red-500' : '' }} rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all cursor-pointer active:scale-[0.98] {{ $isCompleted ? 'opacity-60' : '' }}">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isCompleted ? 'bg-green-100' : ($isHighPriority ? 'bg-red-50' : 'bg-slate-100') }}">
                                @if($isCompleted)
                                    <i class="fa-solid fa-check text-green-600"></i>
                                @else
                                    <i class="fa-solid {{ $typeIcon }} {{ $isHighPriority ? 'text-red-600' : 'text-slate-600' }}"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2 mb-1">
                                    <h3 class="text-slate-900 text-sm font-semibold leading-tight {{ $isCompleted ? 'line-through' : '' }}">{{ $task['title'] ?? 'بدون عنوان' }}</h3>
                                    @if($isHighPriority && !$isCompleted)
                                        <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-lg text-xs font-medium flex-shrink-0">فوری</span>
                                    @endif
                                </div>
                                <p class="text-slate-500 text-xs mb-2 line-clamp-1">{{ $task['description']['full'] ?? '' }}</p>
                                <div class="flex items-center gap-2">
                                    <i class="fa-regular fa-clock text-slate-400 text-xs"></i>
                                    <span class="text-slate-500 text-xs">{{ $task['due_date']['human']['diff'] ?? '—' }}</span>
                                    @if($isCompleted)
                                        <span class="text-green-600 text-xs font-medium mr-2">✓ انجام شده</span>
                                    @endif
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-left text-slate-400 text-xs mt-3"></i>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

    </div>

    {{-- Include Create Task Modal --}}
    <x-pwa::modals.create-task-modal />

    @push('scripts')
        @vite('Applications/PWA/Resources/js/pages/tasks.js')
    @endpush
</x-pwa::layouts.app>
