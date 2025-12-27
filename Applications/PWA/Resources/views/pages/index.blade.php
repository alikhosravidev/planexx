@php
    $currentTab = 'home';

    $userName = $user['full_name'] ?? 'کاربر';
    $firstName = $user['first_name'] ?? 'کاربر';
    $avatar = $user['avatar']['url'] ?? null;
    $department = $user['departments'][0]['name'] ?? '';
    $position = $user['primary_roles'][0]['title'] ?? '';

    $tasksCount = $user['tasks_count'] ?? count($tasks);

    $counts = [
        'tasks' => $tasksCount,
        'pending' => $user['waiting_tasks'] ?? 0,
        'documents' => $user['files_count'] ?? count($documents ?? []),
        'notifications' => $notificationsCount ?? 0,
    ];
@endphp

<x-pwa::layouts.app title="داشبورد" :current-tab="$currentTab" :show-header="false">
    <x-slot:customHeader>
        <!-- Header -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-700 px-5 pt-8 pb-6 rounded-b-[32px] shadow-lg">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white/20">
                        @if($avatar)
                            <img src="{{ $avatar }}" alt="{{ $userName }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-slate-600 flex items-center justify-center">
                                <i class="fa-solid fa-user text-white text-lg"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h1 class="text-white text-lg font-bold">سلام، {{ $firstName }}</h1>
                        <p class="text-white/60 text-xs">{{ $position }} @if($department) • {{ $department }} @endif</p>
                    </div>
                </div>
                <a href="#" class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-sm hover:bg-white/20 transition-all relative">
                    <i class="fa-solid fa-bell text-white text-lg"></i>
                    @if($counts['notifications'] > 0)
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-white text-xs font-bold">{{ $counts['notifications'] }}</span>
                    @endif
                </a>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fa-solid fa-list-check text-emerald-400 text-lg"></i>
                        <span class="text-white/70 text-xs">مجموع کارها</span>
                    </div>
                    <p class="text-white text-2xl font-bold">{{ $tasksCount }}</p>
                    <span class="text-white/50 text-xs">وظیفه فعال</span>
                </div>

                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fa-solid fa-heart text-pink-400 text-lg"></i>
                        <span class="text-white/70 text-xs">روزهای همکاری</span>
                    </div>
                    <p class="text-white text-2xl font-bold">{{ $user['collaboration_days'] ?? '—' }}</p>
                    <span class="text-white/50 text-xs">روز با شرکت</span>
                </div>
            </div>
        </div>
    </x-slot:customHeader>

    <!-- Main Content -->
    <div class="px-5 py-6 space-y-6">

        <!-- Quick Access Boxes -->
        <div class="grid grid-cols-3 gap-3">
            <a href="{{ route('pwa.tasks.index') }}" class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all active:scale-95 group">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-2 group-hover:bg-blue-100 transition-colors relative">
                        <i class="fa-solid fa-tasks text-blue-600 text-xl"></i>
                        @if($counts['tasks'] > 0)
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold">{{ $counts['tasks'] }}</span>
                        @endif
                    </div>
                    <p class="text-slate-900 text-sm font-semibold">وظایف</p>
                </div>
            </a>

            <a href="{{ route('pwa.documents.index') }}" class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all active:scale-95 group">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center mb-2 group-hover:bg-amber-100 transition-colors relative">
                        <i class="fa-solid fa-folder-open text-amber-600 text-xl"></i>
                        @if($counts['documents'] > 0)
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-amber-600 rounded-full flex items-center justify-center text-white text-xs font-bold">{{ $counts['documents'] }}</span>
                        @endif
                    </div>
                    <p class="text-slate-900 text-sm font-semibold">اسناد</p>
                </div>
            </a>

            <a href="#" class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all active:scale-95 group">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center mb-2 group-hover:bg-purple-100 transition-colors relative">
                        <i class="fa-solid fa-bell text-purple-600 text-xl"></i>
                        @if($counts['notifications'] > 0)
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold">{{ $counts['notifications'] }}</span>
                        @endif
                    </div>
                    <p class="text-slate-900 text-sm font-semibold">اطلاعیه‌ها</p>
                </div>
            </a>
        </div>

        <!-- Urgent Tasks (48 hours) -->
        @if(! empty($tasks))
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                        <h2 class="text-slate-900 text-base font-bold">کارهای فوری</h2>
                        <span class="text-slate-500 text-xs">(۴۸ ساعت آینده)</span>
                    </div>
                    <a href="{{ route('pwa.tasks.index') }}" class="text-slate-600 text-xs font-medium flex items-center gap-1 hover:gap-2 transition-all">
                        همه
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    </a>
                </div>
                <div class="space-y-3">
                    @foreach($tasks as $task)
                        @php
                            $isHigh = in_array($task['priority']['name'] ?? '', ['HIGH', 'URGENT']);
                            $taskType = $task['type'] ?? ($task['workflow']['type'] ?? 'task');
                        @endphp
                        <a href="{{ route('pwa.tasks.show', $task['id']) }}" class="block bg-white {{ $isHigh ? 'border-r-4 border-r-red-500' : '' }} rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all cursor-pointer active:scale-[0.98]">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isHigh ? 'bg-red-50' : 'bg-slate-100' }}">
                                    @if($taskType === 'approval')
                                        <i class="fa-solid fa-stamp {{ $isHigh ? 'text-red-600' : 'text-slate-600' }}"></i>
                                    @elseif($taskType === 'meeting')
                                        <i class="fa-solid fa-users {{ $isHigh ? 'text-red-600' : 'text-slate-600' }}"></i>
                                    @else
                                        <i class="fa-solid fa-file-lines {{ $isHigh ? 'text-red-600' : 'text-slate-600' }}"></i>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-slate-900 text-sm font-semibold leading-tight mb-1 line-clamp-2">{{ $task['title'] ?? 'بدون عنوان' }}</h3>
                                    <div class="flex items-center gap-2">
                                        <i class="fa-regular fa-clock text-slate-400 text-xs"></i>
                                        <span class="text-slate-500 text-xs">{{ $task['due_date']['human']['diff'] ?? '—' }}</span>
                                    </div>
                                </div>
                                @if($isHigh)
                                    <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-lg text-xs font-medium flex-shrink-0">فوری</span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('pwa.tasks.index', ['status' => 'pending']) }}" class="flex items-center gap-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-4 text-white hover:shadow-lg transition-all active:scale-95">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fa-solid fa-clock text-lg"></i>
                </div>
                <div>
                    <p class="text-white/80 text-xs">در انتظار</p>
                    <p class="text-white text-lg font-bold">{{ $counts['pending'] }} کار</p>
                </div>
            </a>
            <a href="#" class="flex items-center gap-3 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-4 text-white hover:shadow-lg transition-all active:scale-95">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fa-solid fa-chart-pie text-lg"></i>
                </div>
                <div>
                    <p class="text-white/80 text-xs">گزارش عملکرد</p>
                    <p class="text-white text-lg font-bold">مشاهده</p>
                </div>
            </a>
        </div>

        <!-- Recent Documents -->
        @if(!empty($documents))
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-slate-900 text-base font-bold">اسناد اخیر</h2>
                    <a href="{{ route('pwa.documents.index') }}" class="text-slate-600 text-xs font-medium flex items-center gap-1 hover:gap-2 transition-all">
                        همه
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    </a>
                </div>
                <div class="space-y-2">
                    @foreach($documents as $doc)
                        @php
                            $docType = $doc['mime_type'] ?? $doc['type'] ?? 'file';
                            $isImage = str_contains($docType, 'image');
                            $isPdf = str_contains($docType, 'pdf');
                            $isExcel = str_contains($docType, 'excel') || str_contains($docType, 'spreadsheet');
                        @endphp
                        <div onclick="openFileModal(@js($doc))" class="flex items-center gap-3 bg-white rounded-2xl p-3 shadow-sm hover:shadow-lg transition-all cursor-pointer active:scale-[0.98]">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isPdf ? 'bg-red-50' : ($isExcel ? 'bg-green-50' : 'bg-slate-100') }}">
                                @if($isPdf)
                                    <i class="fa-solid fa-file-pdf text-red-600"></i>
                                @elseif($isExcel)
                                    <i class="fa-solid fa-file-excel text-green-600"></i>
                                @elseif($isImage)
                                    <i class="fa-solid fa-image text-xl text-purple-600"></i>
                                @else
                                    <i class="fa-solid fa-file text-xl text-slate-600"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-slate-900 text-sm font-medium truncate">{{ $doc['title'] ?? $doc['name'] ?? 'بدون عنوان' }}</h3>
                                <p class="text-slate-500 text-xs">{{ $doc['created_at']['human']['date'] ?? $doc['date'] ?? '—' }}</p>
                            </div>
                            <i class="fa-solid fa-chevron-left text-slate-400 text-xs"></i>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    <!-- File Modal -->
    <div id="fileModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-0 sm:p-4" onclick="closeFileModal()">
        <div onclick="event.stopPropagation()" class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-[480px] max-h-[85vh] overflow-hidden shadow-2xl transform transition-all">

            <!-- Modal Header -->
            <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3 flex-1 min-w-0">
                        <div id="fileModalIcon" class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"></div>
                        <div class="flex-1 min-w-0 pt-1">
                            <h2 id="fileModalTitle" class="text-slate-900 text-base font-bold leading-tight mb-1"></h2>
                            <div class="flex items-center gap-2 text-xs">
                                <span id="fileModalType" class="text-slate-500 font-medium"></span>
                                <span class="text-slate-300">•</span>
                                <span id="fileModalSize" class="text-slate-500"></span>
                            </div>
                        </div>
                    </div>
                    <button onclick="closeFileModal()" class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all flex-shrink-0">
                        <i class="fa-solid fa-xmark text-slate-600"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-5 overflow-y-auto max-h-[calc(85vh-160px)]">

                <!-- Description -->
                <div class="mb-6">
                    <p id="fileModalDescription" class="text-slate-700 text-sm leading-relaxed"></p>
                </div>

                <!-- Meta Info -->
                <div class="space-y-3">

                    <!-- Date & Time -->
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center">
                            <i class="fa-regular fa-clock text-slate-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-slate-400 text-xs mb-0.5">زمان آپلود</p>
                            <p id="fileModalDateTime" class="text-slate-900 text-sm font-medium"></p>
                        </div>
                    </div>

                    <!-- Uploader -->
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center">
                            <i class="fa-regular fa-user text-slate-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-slate-400 text-xs mb-0.5">آپلود شده توسط</p>
                            <p id="fileModalUploader" class="text-slate-900 text-sm font-medium"></p>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Modal Footer -->
            <div class="sticky bottom-0 bg-white border-t border-gray-100 px-6 py-4">
                <div class="grid grid-cols-2 gap-3">
                    <button onclick="closeFileModal()" class="h-12 bg-gray-100 hover:bg-gray-200 text-slate-700 rounded-xl font-medium transition-all active:scale-[0.98]">
                        بستن
                    </button>
                    <button onclick="downloadFile()" class="h-12 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-medium transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                        <i class="fa-solid fa-download"></i>
                        دانلود فایل
                    </button>
                </div>
            </div>

        </div>
    </div>

    <x-slot:scripts>
        <script>
            let currentFile = null;

            function openFileModal(file) {
                currentFile = file;
                const modal = document.getElementById('fileModal');
                const modalIcon = document.getElementById('fileModalIcon');
                const modalTitle = document.getElementById('fileModalTitle');
                const modalType = document.getElementById('fileModalType');
                const modalSize = document.getElementById('fileModalSize');
                const modalDescription = document.getElementById('fileModalDescription');
                const modalDateTime = document.getElementById('fileModalDateTime');
                const modalUploader = document.getElementById('fileModalUploader');

                const mimeType = file.mime_type || file.type || 'file';
                const isPdf = mimeType.includes('pdf');
                const isExcel = mimeType.includes('excel') || mimeType.includes('spreadsheet');

                if (isPdf) {
                    modalIcon.className = 'w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 bg-red-50 text-red-600';
                    modalIcon.innerHTML = '<i class="fa-solid fa-file-pdf"></i>';
                } else if (isExcel) {
                    modalIcon.className = 'w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 bg-green-50 text-green-600';
                    modalIcon.innerHTML = '<i class="fa-solid fa-file-excel"></i>';
                } else {
                    modalIcon.className = 'w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 bg-slate-100 text-slate-600';
                    modalIcon.innerHTML = '<i class="fa-solid fa-file"></i>';
                }

                modalTitle.textContent = file.title || file.name || 'بدون عنوان';
                modalType.textContent = file.file_type_label || 'فایل';
                modalSize.textContent = file.file_size_human || '—';
                modalDescription.textContent = file.description || '';
                modalDateTime.textContent = file.created_at?.human?.default || '—';
                modalUploader.textContent = file.uploaded_by?.full_name || 'نامشخص';

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeFileModal() {
                const modal = document.getElementById('fileModal');
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                currentFile = null;
            }

            function downloadFile() {
                if (currentFile && currentFile.id) {
                    window.location.href = '{{ route("pwa.documents.index") }}/' + currentFile.id + '/download';
                } else if (currentFile && currentFile.url) {
                    window.open(currentFile.url, '_blank');
                }
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const fileModal = document.getElementById('fileModal');
                    if (!fileModal.classList.contains('hidden')) {
                        closeFileModal();
                    }
                }
            });
        </script>
    </x-slot:scripts>
</x-pwa::layouts.app>
