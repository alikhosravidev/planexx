<div id="followUpModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4" data-modal data-modal-backdrop>
    <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-hidden shadow-2xl">

        {{-- Modal Header --}}
        <div class="bg-white border-b border-gray-100 px-6 py-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-text-primary">ثبت یادداشت و پیگیری</h2>
                <button type="button" data-modal-close class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all">
                    <i class="fa-solid fa-xmark text-slate-600"></i>
                </button>
            </div>
        </div>

        {{-- Modal Body --}}
        <div class="px-6 py-5 overflow-y-auto max-h-[calc(90vh-160px)]">
            <form id="followUpForm"
                  data-ajax
                  method="POST"
                  action="{{ route('api.v1.admin.bpms.follow-ups.store') }}"
                  enctype="multipart/form-data"
                  data-on-success="reload">
                @csrf

                <input type="hidden" name="task_id" value="{{ $taskId }}">

                {{-- Follow-up Content --}}
                <div class="mb-5">
                    <label class="text-text-secondary text-sm font-medium mb-2 block">متن یادداشت</label>
                    <textarea id="followUpContent" name="content" rows="4"
                              class="w-full border border-border-medium rounded-xl px-4 py-3 text-base text-text-primary resize-none focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                              placeholder="یادداشت یا توضیحات خود را بنویسید..." required></textarea>
                </div>

                {{-- Next Follow-up Date --}}
                <div class="mb-5">
                    <label class="text-text-secondary text-sm font-medium mb-2 block">تاریخ پیگیری بعدی</label>
                    <div class="relative">
                        <input type="date" name="next_follow_up_date"
                               class="w-full border border-border-medium rounded-xl px-4 py-3 text-base text-text-primary focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <i class="fa-regular fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-text-muted pointer-events-none"></i>
                    </div>
                </div>

                {{-- Attach File --}}
                <div class="mb-5">
                    <label class="text-text-secondary text-sm font-medium mb-2 block">پیوست فایل (اختیاری)</label>
                    <label data-drop-zone class="border-2 border-dashed border-border-medium rounded-xl p-6 text-center cursor-pointer hover:border-indigo-500 transition-all block">
                        <input type="file" name="attachment" class="hidden" id="followUpFileInput">
                        <div id="followUpFilePlaceholder">
                            <i class="fa-solid fa-cloud-arrow-up text-text-muted text-2xl mb-2"></i>
                            <p class="text-text-muted text-sm">برای آپلود فایل کلیک کنید</p>
                        </div>
                        <div id="followUpFileSelected" class="hidden">
                            <i class="fa-solid fa-file-check text-green-500 text-2xl mb-2"></i>
                            <p id="followUpFileName" class="text-text-primary text-sm font-medium"></p>
                            <p class="text-text-muted text-xs mt-1">برای تغییر کلیک کنید</p>
                        </div>
                    </label>
                </div>
            </form>
        </div>

        {{-- Modal Footer --}}
        <div class="bg-white border-t border-gray-100 px-6 py-4">
            <div class="grid grid-cols-2 gap-3">
                <button type="button" data-modal-close class="h-12 bg-gray-100 hover:bg-gray-200 text-slate-700 rounded-xl font-medium transition-all">
                    انصراف
                </button>
                <button type="submit" form="followUpForm" class="h-12 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-check"></i>
                    ثبت یادداشت
                </button>
            </div>
        </div>

    </div>
</div>
