<div id="forwardModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4" data-modal data-modal-backdrop>
    <div  class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-visible shadow-2xl">

        {{-- Modal Header --}}
        <div class="bg-white border-b border-gray-100 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-text-primary">ارجاع به مرحله بعد</h2>
                    @if($currentState || $nextState)
                        <p class="text-text-muted text-sm mt-0.5">
                            {{ $currentState['name'] ?? '-' }}
                            <i class="fa-solid fa-arrow-left mx-2 text-xs"></i>
                            {{ $nextState['name'] ?? 'تکمیل' }}
                        </p>
                    @endif
                </div>
                <button type="button" data-modal-close class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        {{-- Modal Body --}}
        <div class="px-6 py-5 overflow-visible">
            <form id="forwardForm"
                  data-ajax
                  data-method="PUT"
                  action="{{ $taskId ? route('api.v1.admin.bpms.tasks.update', ['task' => $taskId]) : '#' }}"
                  data-on-success="reload">
                @csrf

                <input type="hidden" name="action" value="forward">
                @if($nextState)
                    <input type="hidden" name="next_state_id" value="{{ $nextState['id'] }}">
                @endif

                {{-- Forward Note --}}
                <div class="mb-5">
                    <x-panel::forms.textarea
                        name="description"
                        label="توضیحات ارجاع"
                        placeholder="توضیحات لازم برای نفر بعدی..."
                        rows="3"
                        class="min-w-[120px]"
                    />
                </div>

                {{-- Select Assignee --}}
                <x-panel::forms.tom-select-ajax
                    name="assignee"
                    label="مسئول انجام"
                    placeholder="جستجو و انتخاب مسئول"
                    required
                    :url="route('api.v1.admin.org.users.keyValList', ['per_page' => 100, 'field' => 'full_name', 'filter' => ['user_type' => 2]])"
                    class="min-w-[120px]"
                />
            </form>
        </div>

        {{-- Modal Footer --}}
        <div class="bg-white border-t border-gray-100 px-6 py-4">
            <div class="grid grid-cols-2 gap-3">
                <button type="button" data-modal-close class="h-12 bg-gray-100 hover:bg-gray-200 text-slate-700 rounded-xl font-medium transition-all">
                    انصراف
                </button>
                <button type="submit" form="forwardForm" class="h-12 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    ارجاع کار
                </button>
            </div>
        </div>

    </div>
</div>
