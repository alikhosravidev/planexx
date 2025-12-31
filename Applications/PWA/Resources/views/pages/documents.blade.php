@php
    $currentTab = 'home';
    $pageTitle = 'اسناد من';

    $typeIcons = [
        'document' => ['icon' => 'fa-file-pdf', 'color' => 'text-red-600', 'bg' => 'bg-red-100'],
        'excel'    => ['icon' => 'fa-file-excel', 'color' => 'text-green-600', 'bg' => 'bg-green-100'],
        'word'     => ['icon' => 'fa-file-word', 'color' => 'text-blue-600', 'bg' => 'bg-blue-100'],
        'image'    => ['icon' => 'fa-file-image', 'color' => 'text-purple-600', 'bg' => 'bg-purple-100'],
        'video'    => ['icon' => 'fa-file-video', 'color' => 'text-pink-600', 'bg' => 'bg-pink-100'],
        'audio'    => ['icon' => 'fa-file-audio', 'color' => 'text-yellow-600', 'bg' => 'bg-yellow-100'],
        'archive'  => ['icon' => 'fa-file-zipper', 'color' => 'text-amber-600', 'bg' => 'bg-amber-100'],
        'default'  => ['icon' => 'fa-file', 'color' => 'text-blue-600', 'bg' => 'bg-blue-100'],
    ];

    $counts = $counts ?? [
        'all' => count($documents ?? []),
        'contract' => 0,
        'report' => 0,
        'payroll' => 0,
        'document' => 0,
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
                <button class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center hover:bg-gray-200 transition-all">
                    <i class="fa-solid fa-search text-slate-600"></i>
                </button>
            </div>

            <!-- Filter Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 -mx-5 px-5 scrollbar-hide">{{--
                <a href="{{ route('pwa.documents.index', ['type' => 'all']) }}"
                   class="flex-shrink-0 px-4 py-2 rounded-xl text-sm font-medium transition-all {{ $activeFilter === 'all' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-slate-600 hover:bg-gray-200' }}">
                    همه ({{ $counts['all'] }})
                </a>
                <a href="{{ route('pwa.documents.index', ['type' => 'payroll']) }}"
                   class="flex-shrink-0 px-4 py-2 rounded-xl text-sm font-medium transition-all {{ $activeFilter === 'payroll' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-slate-600 hover:bg-gray-200' }}">
                    <i class="fa-solid fa-money-bill ml-1.5"></i>
                    فیش حقوقی
                </a>
                <a href="{{ route('pwa.documents.index', ['type' => 'contract']) }}"
                   class="flex-shrink-0 px-4 py-2 rounded-xl text-sm font-medium transition-all {{ $activeFilter === 'contract' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-slate-600 hover:bg-gray-200' }}">
                    <i class="fa-solid fa-file-contract ml-1.5"></i>
                    قراردادها
                </a>
                <a href="{{ route('pwa.documents.index', ['type' => 'report']) }}"
                   class="flex-shrink-0 px-4 py-2 rounded-xl text-sm font-medium transition-all {{ $activeFilter === 'report' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-slate-600 hover:bg-gray-200' }}">
                    <i class="fa-solid fa-chart-bar ml-1.5"></i>
                    گزارشات
                </a>
                <a href="{{ route('pwa.documents.index', ['type' => 'document']) }}"
                   class="flex-shrink-0 px-4 py-2 rounded-xl text-sm font-medium transition-all {{ $activeFilter === 'document' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-slate-600 hover:bg-gray-200' }}">
                    <i class="fa-solid fa-file-lines ml-1.5"></i>
                    مستندات
                </a>
            --}}</div>
        </div>
    </x-slot:customHeader>

    <!-- Main Content -->
    <div class="px-5 py-6">

        @if(empty($documents))
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-16">
                <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fa-solid fa-folder-open text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-slate-900 text-base font-semibold mb-2">فایلی یافت نشد</h3>
                <p class="text-slate-500 text-sm text-center">هیچ فایلی در این دسته‌بندی وجود ندارد</p>
            </div>
        @else
            <!-- Documents List -->
            <div class="space-y-3">
                @foreach($documents as $doc)
                    @php
                        $fileTypeLabel = $doc['file_type_label'] ?? 'default';
                        $typeStyle = $typeIcons[$fileTypeLabel] ?? $typeIcons['default'];
                        $isNew = isset($doc['created_at']) && strtotime($doc['created_at']['raw'] ?? '') > strtotime('-2 days');
                    @endphp
                    <div data-modal-open="fileModal" data-modal-data='@json($doc)' class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all cursor-pointer active:scale-[0.98] {{ $isNew ? 'border-r-4 border-r-blue-500' : '' }}">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 {{ $typeStyle['bg'] }}">
                                <i class="fa-solid {{ $typeStyle['icon'] }} text-xl {{ $typeStyle['color'] }}"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="text-slate-900 text-sm font-semibold truncate">{{ $doc['title'] ?? $doc['name'] ?? 'بدون عنوان' }}</h3>
                                    @if($isNew)
                                        <span class="bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded text-[10px] font-medium flex-shrink-0">جدید</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3 text-slate-500 text-xs">
                                    <span>{{ $doc['file_size'] ?? '—' }}</span>
                                    <span>•</span>
                                    <span>{{ $doc['created_at']['human']['diff'] ?? $doc['created_at']['human']['date'] ?? '—' }}</span>
                                </div>
                            </div>
                            <a href="{{ route('pwa.documents.download', $doc['id']) }}" onclick="event.stopPropagation()" class="w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-gray-200 transition-all">
                                <i class="fa-solid fa-download text-slate-600 text-sm"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

    <!-- File Modal -->
    <div id="fileModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-0 sm:p-4" data-modal data-modal-backdrop>
        <div class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-[480px] max-h-[85vh] overflow-hidden shadow-2xl transform transition-all">

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
                    <button type="button" data-modal-close class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all flex-shrink-0">
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
                    <button type="button" data-modal-close class="h-12 bg-gray-100 hover:bg-gray-200 text-slate-700 rounded-xl font-medium transition-all active:scale-[0.98]">
                        بستن
                    </button>
                    <button type="button" id="downloadFileBtn" class="h-12 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-medium transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                        <i class="fa-solid fa-download"></i>
                        دانلود فایل
                    </button>
                </div>
            </div>

        </div>
    </div>

    <x-slot:scripts>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('fileModal');
                const typeIcons = {
                    'document': { icon: 'fa-file-pdf', bg: 'bg-red-100', color: 'text-red-600' },
                    'excel': { icon: 'fa-file-excel', bg: 'bg-green-100', color: 'text-green-600' },
                    'word': { icon: 'fa-file-word', bg: 'bg-blue-100', color: 'text-blue-600' },
                    'image': { icon: 'fa-file-image', bg: 'bg-purple-100', color: 'text-purple-600' },
                    'video': { icon: 'fa-file-video', bg: 'bg-pink-100', color: 'text-pink-600' },
                    'audio': { icon: 'fa-file-audio', bg: 'bg-yellow-100', color: 'text-yellow-600' },
                    'archive': { icon: 'fa-file-zipper', bg: 'bg-amber-100', color: 'text-amber-600' },
                    'default': { icon: 'fa-file', bg: 'bg-blue-100', color: 'text-blue-600' }
                };

                let currentFile = null;

                modal.addEventListener('modal:data-loaded', function(e) {
                    currentFile = e.detail;
                    const fileTypeLabel = currentFile.file_type_label || 'default';
                    const style = typeIcons[fileTypeLabel] || typeIcons['default'];

                    const modalIcon = document.getElementById('fileModalIcon');
                    const modalTitle = document.getElementById('fileModalTitle');
                    const modalType = document.getElementById('fileModalType');
                    const modalSize = document.getElementById('fileModalSize');
                    const modalDescription = document.getElementById('fileModalDescription');
                    const modalDateTime = document.getElementById('fileModalDateTime');
                    const modalUploader = document.getElementById('fileModalUploader');

                    modalIcon.className = `w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 ${style.bg} ${style.color}`;
                    modalIcon.innerHTML = `<i class="fa-solid ${style.icon}"></i>`;

                    modalTitle.textContent = currentFile.title || currentFile.name || 'بدون عنوان';
                    modalType.textContent = currentFile.file_type_label || 'فایل';
                    modalSize.textContent = currentFile.file_size_human || '—';
                    modalDescription.textContent = currentFile.description || '';
                    modalDateTime.textContent = currentFile.created_at?.human?.default || '—';
                    modalUploader.textContent = currentFile.uploaded_by?.full_name || 'نامشخص';
                });

                document.getElementById('downloadFileBtn').addEventListener('click', function() {
                    if (currentFile && currentFile.id) {
                        window.location.href = '{{ route("pwa.documents.index") }}/' + currentFile.id + '/download';
                    } else if (currentFile && currentFile.url) {
                        window.open(currentFile.url, '_blank');
                    }
                });
            });
        </script>
    </x-slot:scripts>
</x-pwa::layouts.app>
