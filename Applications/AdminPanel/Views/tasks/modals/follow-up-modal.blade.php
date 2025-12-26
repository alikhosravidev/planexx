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
                  data-method="PUT"
                  action="{{ $taskId ? route('api.v1.admin.bpms.tasks.update', ['task' => $taskId]) : '#' }}"
                  enctype="multipart/form-data"
                  data-on-success="reload">
                @csrf

                <input type="hidden" name="action" value="add_note">

                {{-- Follow-up Content --}}
                <div class="mb-5">
                    <x-panel::forms.textarea
                        name="content"
                        label="متن یادداشت"
                        placeholder="توضیحات لازم برای نفر بعدی..."
                        rows="4"
                        required
                        class="min-w-[140px]"
                    />
                </div>

                {{-- Next Follow-up Date --}}
                <div class="mb-5">
                    <x-panel::forms.date
                        name="next_follow_up_date"
                        label="تاریخ پیگیری بعدی"
                        class="min-w-[140px]"
                    />
                </div>

                {{-- Attach File --}}
                <div class="mb-5">
                    <label class="text-text-secondary text-sm font-medium mb-2 block">پیوست فایل (اختیاری)</label>
                    <x-panel::file-drop-zone
                        name="attachment"
                        :required="false"
                        placeholderText="برای آپلود فایل کلیک کنید"
                        placeholderSubtext=""
                        selectFileText="آپلود"
                    />
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
