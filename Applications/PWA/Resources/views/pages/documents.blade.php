@php
    $currentTab = 'home';
    $pageTitle = 'اسناد من';

    $typeIcons = [
        'pdf'   => ['icon' => 'fa-file-pdf', 'color' => 'text-red-600', 'bg' => 'bg-red-50'],
        'excel' => ['icon' => 'fa-file-excel', 'color' => 'text-green-600', 'bg' => 'bg-green-50'],
        'word'  => ['icon' => 'fa-file-word', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50'],
        'ppt'   => ['icon' => 'fa-file-powerpoint', 'color' => 'text-orange-600', 'bg' => 'bg-orange-50'],
        'image' => ['icon' => 'fa-image', 'color' => 'text-purple-600', 'bg' => 'bg-purple-50'],
        'default' => ['icon' => 'fa-file', 'color' => 'text-slate-600', 'bg' => 'bg-slate-100'],
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
                        $mimeType = $doc['mime_type'] ?? '';
                        $fileType = 'default';

                        if (str_contains($mimeType, 'pdf')) {
                            $fileType = 'pdf';
                        } elseif (str_contains($mimeType, 'excel') || str_contains($mimeType, 'spreadsheet')) {
                            $fileType = 'excel';
                        } elseif (str_contains($mimeType, 'word') || str_contains($mimeType, 'document')) {
                            $fileType = 'word';
                        } elseif (str_contains($mimeType, 'powerpoint') || str_contains($mimeType, 'presentation')) {
                            $fileType = 'ppt';
                        } elseif (str_contains($mimeType, 'image')) {
                            $fileType = 'image';
                        }

                        $typeStyle = $typeIcons[$fileType];
                        $isNew = isset($doc['created_at']) && strtotime($doc['created_at']['raw'] ?? '') > strtotime('-2 days');
                    @endphp
                    <div onclick="openFileModal(@js($doc))" class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all cursor-pointer active:scale-[0.98] {{ $isNew ? 'border-r-4 border-r-blue-500' : '' }}">
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
                const isWord = mimeType.includes('word') || mimeType.includes('document');
                const isImage = mimeType.includes('image');

                if (isPdf) {
                    modalIcon.className = 'w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 bg-red-50 text-red-600';
                    modalIcon.innerHTML = '<i class="fa-solid fa-file-pdf"></i>';
                } else if (isExcel) {
                    modalIcon.className = 'w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 bg-green-50 text-green-600';
                    modalIcon.innerHTML = '<i class="fa-solid fa-file-excel"></i>';
                } else if (isWord) {
                    modalIcon.className = 'w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 bg-blue-50 text-blue-600';
                    modalIcon.innerHTML = '<i class="fa-solid fa-file-word"></i>';
                } else if (isImage) {
                    modalIcon.className = 'w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 bg-purple-50 text-purple-600';
                    modalIcon.innerHTML = '<i class="fa-solid fa-image"></i>';
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

        <style>
            .scrollbar-hide::-webkit-scrollbar {
                display: none;
            }
            .scrollbar-hide {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>
    </x-slot:scripts>
</x-pwa::layouts.app>
