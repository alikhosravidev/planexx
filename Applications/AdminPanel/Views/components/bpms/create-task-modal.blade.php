@props([
    'task' => null,
])

@php
    $isEdit = isset($task['id']);
    $priorityOptions = [
        0 => 'کم',
        1 => 'متوسط',
        2 => 'بالا',
        3 => 'فوری',
    ];
@endphp

<div id="taskModal"
     data-modal
     data-modal-backdrop
     class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden shadow-2xl">

        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4">
            <div class="flex items-center justify-between">
                <h2 id="taskModalTitle" class="text-lg font-bold text-text-primary">افزودن کار جدید</h2>
                <button type="button" data-modal-close
                        class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-text-primary hover:bg-gray-100 rounded-lg transition-all">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
        </div>

        <div class="px-6 py-5 overflow-y-auto max-h-[calc(90vh-160px)]">
            <form id="taskForm"
                  data-ajax
                  data-method="POST"
                  action="{{ route('api.v1.admin.bpms.tasks.store') }}"
                  data-on-success="redirect"
                  data-loading-class="opacity-50 pointer-events-none">
                @csrf

                {{-- Step 1: Workflow Selection --}}
                <div id="taskStep1" class="space-y-5">
                    <div class="text-center mb-6">
                        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid fa-diagram-project text-indigo-600 text-xl"></i>
                        </div>
                        <h3 class="text-base font-bold text-text-primary">انتخاب فرایند</h3>
                        <p class="text-sm text-text-muted mt-1">کار جدید را در کدام فرایند ایجاد می‌کنید؟</p>
                    </div>

                    <div id="workflowOptions" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    </div>

                    <div id="workflowLoading" class="text-center py-8">
                        <i class="fa-solid fa-spinner fa-spin text-indigo-600 text-2xl"></i>
                        <p class="text-sm text-text-muted mt-2">در حال بارگذاری فرایندها...</p>
                    </div>
                </div>

                {{-- Step 2: Task Details --}}
                <div id="taskStep2" class="hidden space-y-5">

                    {{-- Selected Workflow Badge --}}
                    <div id="selectedWorkflowBadge" class="flex items-center gap-2 px-4 py-3 bg-indigo-50 border border-indigo-200 rounded-xl">
                        <i class="fa-solid fa-diagram-project text-indigo-600"></i>
                        <span class="text-sm font-medium text-indigo-700" id="selectedWorkflowName"></span>
                        <button type="button" id="changeWorkflowBtn" class="mr-auto text-indigo-600 hover:text-indigo-800 text-sm">
                            <i class="fa-solid fa-pen ml-1"></i>
                            تغییر
                        </button>
                    </div>

                    <input type="hidden" name="workflow_id" id="selectedWorkflowId">

                    {{-- Title --}}
                    <x-panel::forms.input
                        name="title"
                        label="عنوان کار"
                        placeholder="عنوان کار را وارد کنید..."
                        required
                        class="min-w-[120px]"
                    />

                    {{-- Assignee --}}
                    <div>
                        <x-panel::forms.tom-select-ajax
                            name="assignee"
                            label="مسئول انجام"
                            placeholder="جستجو و انتخاب مسئول"
                            required
                            :url="route('api.v1.admin.org.users.keyValList', ['per_page' => 100, 'field' => 'full_name', 'filter' => ['user_type' => 2]])"
                            class="min-w-[120px]"
                        />
                    </div>

                    {{-- Description --}}
                    <x-panel::forms.textarea
                        name="description"
                        label="توضیحات"
                        placeholder="توضیحات بیشتر درباره کار..."
                        rows="3"
                        class="min-w-[120px]"
                    />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Priority --}}
                        <x-panel::forms.select
                            name="priority"
                            label="اولویت"
                            :options="$priorityOptions"
                            value="1"
                            class="min-w-[80px]"
                        />

                        {{-- Due Date --}}
                        <x-panel::forms.date
                            name="due_date"
                            label="ددلاین"
                            class="min-w-[80px]"
                        />
                    </div>

                    {{-- Selected Assignee --}}
                    <div id="selectedAssigneeDisplay" class="hidden mt-2 flex items-center gap-2 px-3 py-2 bg-green-50 border border-green-200 rounded-lg">
                        <img id="selectedAssigneeAvatar" src="" alt="" class="w-6 h-6 rounded-full object-cover">
                        <span id="selectedAssigneeName" class="text-sm font-medium text-green-700"></span>
                        <button type="button" id="clearAssigneeBtn" class="mr-auto text-green-600 hover:text-green-800">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>

                    {{-- Default Assignee Notice --}}
                    <div id="defaultAssigneeNotice" class="hidden mt-2 flex items-center gap-2 px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg">
                        <i class="fa-solid fa-info-circle text-blue-500"></i>
                        <span class="text-xs text-blue-700">مسئول پیش‌فرض این مرحله انتخاب شده است</span>
                    </div>

                    {{-- Estimated Hours --}}
                    <x-panel::forms.input
                        name="estimated_hours"
                        label="زمان تخمینی (ساعت)"
                        type="number"
                        placeholder="مثلاً: 2"
                        min="0"
                        step="0.5"
                    />
                </div>

            </form>
        </div>

        {{-- Modal Footer --}}
        <div id="taskModalFooter" class="hidden sticky bottom-0 bg-white border-t border-gray-100 px-6 py-4">
            <div class="flex items-center gap-3">
                <button type="button" data-modal-close
                        class="flex-1 px-6 py-3 text-base font-medium text-text-secondary bg-gray-100 rounded-xl hover:bg-gray-200 transition-all">
                    انصراف
                </button>
                <button type="submit" form="taskForm" id="submitTaskBtn"
                        class="flex-1 px-6 py-3 text-base font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-check"></i>
                    <span id="submitTaskBtnText">ایجاد کار</span>
                </button>
            </div>
        </div>

    </div>
</div>
