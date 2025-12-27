@props([
    'taskId' => null,
])

<!-- Follow-up Modal -->
<div id="followUpModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div onclick="event.stopPropagation()" class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-[480px] max-h-[90vh] overflow-hidden shadow-2xl">

        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4">
            <div class="flex items-center justify-between">
                <h2 class="text-slate-900 text-lg font-bold">ثبت یادداشت و پیگیری</h2>
                <button onclick="closeFollowUpModal()" class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all">
                    <i class="fa-solid fa-xmark text-slate-600"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="px-6 py-5 overflow-y-auto max-h-[calc(90vh-160px)]">

            <!-- Follow-up Content -->
            <div class="mb-5">
                <label class="text-slate-700 text-sm font-medium mb-2 block">متن یادداشت</label>
                <textarea id="followUpContent" rows="4" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-slate-900 resize-none focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-400" placeholder="یادداشت یا توضیحات خود را بنویسید..."></textarea>
            </div>

            <!-- Next Follow-up Date -->
            <div class="mb-5">
                <label class="text-slate-700 text-sm font-medium mb-2 block">تاریخ پیگیری بعدی</label>
                <div class="relative">
                    <input type="text" id="nextFollowUpDate" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-400" placeholder="۱۴۰۳/۰۹/۲۷">
                    <i class="fa-regular fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
            </div>

            <!-- Attach File -->
            <div class="mb-5">
                <label class="text-slate-700 text-sm font-medium mb-2 block">پیوست فایل (اختیاری)</label>
                <label class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center cursor-pointer hover:border-slate-400 transition-all block">
                    <input type="file" id="followUpFile" class="hidden" onchange="handleFollowUpFileSelect(this)">
                    <div id="fileUploadPlaceholder">
                        <i class="fa-solid fa-cloud-arrow-up text-slate-400 text-2xl mb-2"></i>
                        <p class="text-slate-500 text-sm">برای آپلود فایل کلیک کنید</p>
                    </div>
                    <div id="fileUploadSelected" class="hidden">
                        <i class="fa-solid fa-file-check text-green-500 text-2xl mb-2"></i>
                        <p id="selectedFileName" class="text-slate-700 text-sm font-medium"></p>
                        <p class="text-slate-400 text-xs mt-1">برای تغییر کلیک کنید</p>
                    </div>
                </label>
            </div>

        </div>

        <!-- Modal Footer -->
        <div class="sticky bottom-0 bg-white border-t border-gray-100 px-6 py-4">
            <div class="grid grid-cols-2 gap-3">
                <button onclick="closeFollowUpModal()" class="h-12 bg-gray-100 hover:bg-gray-200 text-slate-700 rounded-xl font-medium transition-all active:scale-[0.98]">
                    انصراف
                </button>
                <button onclick="submitFollowUp()" class="h-12 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-medium transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    <i class="fa-solid fa-check"></i>
                    ثبت یادداشت
                </button>
            </div>
        </div>

    </div>
</div>

<script>
    function handleFollowUpFileSelect(input) {
        const placeholder = document.getElementById('fileUploadPlaceholder');
        const selected = document.getElementById('fileUploadSelected');
        const fileName = document.getElementById('selectedFileName');

        if (input.files && input.files[0]) {
            placeholder.classList.add('hidden');
            selected.classList.remove('hidden');
            fileName.textContent = input.files[0].name;
        }
    }

    function submitFollowUp() {
        const content = document.getElementById('followUpContent').value;
        const nextDate = document.getElementById('nextFollowUpDate').value;

        if (!content.trim()) {
            alert('لطفاً متن یادداشت را وارد کنید');
            return;
        }

        alert('یادداشت با موفقیت ثبت شد');
        closeFollowUpModal();
        location.reload();
    }
</script>

<style>
    #followUpModal > div {
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>
