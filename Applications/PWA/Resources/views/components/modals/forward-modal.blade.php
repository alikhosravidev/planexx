@props([
    'taskId' => null,
    'currentState' => null,
])

@php
    $nextStateName = 'مرحله بعد';
@endphp

<!-- Forward Modal -->
<div id="forwardModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div onclick="event.stopPropagation()" class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-[480px] max-h-[90vh] overflow-hidden shadow-2xl">

        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-slate-900 text-lg font-bold">ارجاع به مرحله بعد</h2>
                    <p class="text-slate-500 text-xs mt-0.5">{{ $nextStateName }}</p>
                </div>
                <button onclick="closeForwardModal()" class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all">
                    <i class="fa-solid fa-xmark text-slate-600"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="px-6 py-5 overflow-y-auto max-h-[calc(90vh-160px)]">

            <!-- Forward Note -->
            <div class="mb-5">
                <label class="text-slate-700 text-sm font-medium mb-2 block">توضیحات ارجاع</label>
                <textarea id="forwardNote" rows="3" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-slate-900 resize-none focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-400" placeholder="توضیحات لازم برای نفر بعدی..."></textarea>
            </div>

            <!-- Select Assignee -->
            <div class="mb-5">
                <label class="text-slate-700 text-sm font-medium mb-3 block">انتخاب مسئول بعدی</label>
                <div id="assigneeList" class="grid grid-cols-3 gap-3 max-h-[300px] overflow-y-auto p-1">
                    <!-- Assignees will be loaded dynamically -->
                    <div class="col-span-3 text-center py-8 text-slate-500 text-sm">
                        <i class="fa-solid fa-spinner fa-spin text-2xl mb-2"></i>
                        <p>در حال بارگذاری...</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Modal Footer -->
        <div class="sticky bottom-0 bg-white border-t border-gray-100 px-6 py-4">
            <div class="grid grid-cols-2 gap-3">
                <button onclick="closeForwardModal()" class="h-12 bg-gray-100 hover:bg-gray-200 text-slate-700 rounded-xl font-medium transition-all active:scale-[0.98]">
                    انصراف
                </button>
                <button onclick="submitForward()" class="h-12 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    ارجاع کار
                </button>
            </div>
        </div>

    </div>
</div>

<script>
    function submitForward() {
        const assignee = document.querySelector('input[name="assignee"]:checked');
        const note = document.getElementById('forwardNote').value;

        if (!assignee) {
            alert('لطفاً مسئول بعدی را انتخاب کنید');
            return;
        }

        alert('کار با موفقیت ارجاع داده شد');
        closeForwardModal();
        window.location.href = '{{ route("pwa.tasks.index") }}';
    }
</script>

<style>
    #forwardModal > div {
        animation: slideUp 0.3s ease-out;
    }
</style>
