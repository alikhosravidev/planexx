@props([
    'taskId' => null,
    'currentState' => null,
    'nextState' => null,
])

<!-- Forward Modal -->
<div id="forwardModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-0 sm:p-4" data-modal data-modal-backdrop>
    <div onclick="event.stopPropagation()" class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-[480px] max-h-[90vh] overflow-hidden shadow-2xl">

        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-slate-900 text-lg font-bold">ارجاع به مرحله بعد</h2>
                    <p class="text-slate-500 text-xs mt-0.5">{{ $nextState['name'] ?? 'مرحله بعد' }}</p>
                </div>
                <button type="button" data-modal-close class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all">
                    <i class="fa-solid fa-xmark text-slate-600"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="px-6 py-5 overflow-y-auto max-h-[calc(90vh-220px)]">
            <form id="forwardForm"
                  data-ajax
                  data-method="PUT"
                  action="{{ $taskId ? route('api.v1.admin.bpms.tasks.update', ['task' => $taskId]) : '#' }}"
                  data-on-success="redirect"
                  data-redirect-url="{{ route('pwa.tasks.index') }}">
                @csrf

                <input type="hidden" name="action" value="forward">
                @if($nextState)
                    <input type="hidden" name="next_state_id" value="{{ $nextState['id'] }}">
                @endif

                <!-- Forward Note -->
                <div class="mb-5">
                    <div class="border border-gray-200 rounded-xl overflow-hidden focus-within:border-slate-900 focus-within:ring-2 focus-within:ring-slate-900/10 transition-all duration-200">
                        <div class="flex items-stretch">
                            <label for="description" class="bg-slate-50 border-l border-gray-200 px-4 py-3.5 text-sm text-slate-600 flex items-center leading-normal min-w-[120px]">
                                توضیحات ارجاع
                            </label>
                            <textarea
                                name="description"
                                id="description"
                                rows="3"
                                placeholder="توضیحات لازم برای نفر بعدی..."
                                class="flex-1 px-4 py-3.5 text-sm text-slate-900 outline-none bg-transparent leading-normal resize-none"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- Select Assignee -->
                <div class="ts-field-wrapper mb-5">
                    <label for="assignee" class="ts-field-label">
                        مسئول انجام
                        <span class="text-red-500">*</span>
                    </label>
                    <select
                        name="assignee"
                        id="assignee"
                        data-tom-select-ajax
                        data-url="{{ route('api.v1.admin.org.users.keyValList', ['per_page' => 100, 'field' => 'full_name', 'filter' => ['user_type' => 2]]) }}"
                        data-placeholder="جستجو و انتخاب مسئول"
                        data-value-field="id"
                        data-label-field="label"
                        data-path="result"
                        data-template="keyValList"
                        required
                    ></select>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="sticky bottom-0 bg-white border-t border-gray-100 px-6 py-4">
            <div class="grid grid-cols-2 gap-3">
                <button type="button" data-modal-close class="h-12 bg-gray-100 hover:bg-gray-200 text-slate-700 rounded-xl font-medium transition-all active:scale-[0.98]">
                    انصراف
                </button>
                <button type="submit" form="forwardForm" class="h-12 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    ارجاع کار
                </button>
            </div>
        </div>

    </div>
</div>
