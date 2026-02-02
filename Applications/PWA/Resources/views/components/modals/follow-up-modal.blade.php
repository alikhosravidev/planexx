@props([
    'taskId' => null,
])

<!-- Follow-up Modal -->
<div id="followUpModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-0 sm:p-4" data-modal data-modal-backdrop>
    <div class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-[480px] max-h-[90vh] overflow-hidden shadow-2xl">

        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4">
            <div class="flex items-center justify-between">
                <h2 class="text-slate-900 text-lg font-bold">ثبت یادداشت و پیگیری</h2>
                <button type="button" data-modal-close class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all">
                    <i class="fa-solid fa-xmark text-slate-600"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="px-6 py-5 overflow-y-auto max-h-[calc(90vh-160px)]">
            <form id="followUpForm"
                  data-ajax
                  data-method="PUT"
                  action="{{ $taskId ? route('api.v1.client.bpms.tasks.update', ['task' => $taskId]) : '#' }}"
                  enctype="multipart/form-data"
                  data-on-success="reload">
                @csrf

                <input type="hidden" name="action" value="add_note">

                <!-- Follow-up Content -->
                <div class="mb-5">
                    <div class="border border-gray-200 rounded-xl overflow-hidden focus-within:border-slate-900 focus-within:ring-2 focus-within:ring-slate-900/10 transition-all duration-200">
                        <div class="flex items-stretch">
                            <label for="content" class="bg-slate-50 border-l border-gray-200 px-4 py-3.5 text-sm text-slate-600 flex items-center leading-normal min-w-[140px]">
                                متن یادداشت
                                <span class="text-red-500 mr-1">*</span>
                            </label>
                            <textarea
                                name="content"
                                id="content"
                                rows="4"
                                placeholder="یادداشت یا توضیحات خود را بنویسید..."
                                required
                                class="flex-1 px-4 py-3.5 text-sm text-slate-900 outline-none bg-transparent leading-normal resize-none"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- Next Follow-up Date -->
                <div class="mb-5">
                    <div class="border border-gray-200 rounded-xl overflow-hidden focus-within:border-slate-900 focus-within:ring-2 focus-within:ring-slate-900/10 transition-all duration-200">
                        <div class="flex items-stretch">
                            <label for="next_follow_up_date" class="bg-slate-50 border-l border-gray-200 px-4 py-3.5 text-sm text-slate-600 flex items-center leading-normal min-w-[140px]">
                                تاریخ پیگیری بعدی
                            </label>
                            <input
                                type="text"
                                name="next_follow_up_date"
                                id="next_follow_up_date"
                                data-datetimepicker
                                data-datepicker-format="YYYY-0M-0D 0h:0m"
                                data-datepicker-gregorian="true"
                                data-datepicker-time="true"
                                placeholder="انتخاب تاریخ و زمان"
                                readonly
                                class="flex-1 px-4 py-3.5 text-sm text-slate-900 outline-none bg-transparent leading-normal cursor-pointer"
                            />
                        </div>
                    </div>
                </div>

                <!-- Attach File -->
                <div class="mb-5">
                    <label class="text-slate-700 text-sm font-medium mb-2 block">پیوست فایل (اختیاری)</label>
                    <label
                        data-drop-zone
                        class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center cursor-pointer hover:border-slate-400 hover:bg-slate-50 transition-all block">
                        <input type="file" name="attachment" class="hidden" data-file-input>
                        <div data-file-placeholder>
                            <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-cloud-arrow-up text-xl text-slate-600"></i>
                            </div>
                            <p class="text-sm font-medium text-slate-700 mb-1">برای آپلود فایل کلیک کنید</p>
                            <p class="text-xs text-slate-500">یا فایل را اینجا رها کنید</p>
                        </div>
                        <div data-file-selected class="hidden">
                            <i class="fa-solid fa-file-check text-green-500 text-2xl mb-2"></i>
                            <p data-file-name class="text-slate-700 text-sm font-medium"></p>
                            <p class="text-slate-400 text-xs mt-1">برای تغییر کلیک کنید</p>
                        </div>
                    </label>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="sticky bottom-0 bg-white border-t border-gray-100 px-6 py-4">
            <div class="grid grid-cols-2 gap-3">
                <button type="button" data-modal-close class="h-12 bg-gray-100 hover:bg-gray-200 text-slate-700 rounded-xl font-medium transition-all active:scale-[0.98]">
                    انصراف
                </button>
                <button type="submit" form="followUpForm" class="h-12 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-medium transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    <i class="fa-solid fa-check"></i>
                    ثبت یادداشت
                </button>
            </div>
        </div>

    </div>
</div>

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
