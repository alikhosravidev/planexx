@php
    $priorityOptions = [
        0 => 'کم',
        1 => 'متوسط',
        2 => 'بالا',
        3 => 'فوری',
    ];
@endphp

<!-- Create Task Modal -->
<div id="createTaskModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-0 sm:p-4" data-modal data-modal-backdrop>
    <div class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-[520px] max-h-[90vh] overflow-hidden shadow-2xl">

        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4">
            <div class="flex items-center justify-between">
                <h2 id="createTaskModalTitle" class="text-slate-900 text-lg font-bold">افزودن کار جدید</h2>
                <button type="button" data-modal-close class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all">
                    <i class="fa-solid fa-xmark text-slate-600"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="px-6 py-5 overflow-y-auto max-h-[calc(90vh-160px)]">
            <form id="createTaskForm"
                  data-ajax
                  data-method="POST"
                  action="{{ route('api.v1.client.bpms.tasks.store') }}"
                  data-on-success="reload">
                @csrf

                {{-- Step 1: Workflow Selection --}}
                <div id="taskStep1" class="space-y-4">
                    <div class="text-center mb-5">
                        <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid fa-diagram-project text-slate-700 text-2xl"></i>
                        </div>
                        <h3 class="text-slate-900 text-base font-bold mb-1">انتخاب فرایند</h3>
                        <p class="text-slate-500 text-sm">کار جدید را در کدام فرایند ایجاد می‌کنید؟</p>
                    </div>

                    <div id="workflowOptions" class="space-y-2">
                    </div>

                    <div id="workflowLoading" class="text-center py-8">
                        <div class="w-10 h-10 border-3 border-slate-200 border-t-slate-700 rounded-full animate-spin mx-auto mb-3"></div>
                        <p class="text-sm text-slate-500">در حال بارگذاری فرایندها...</p>
                    </div>

                    <div id="workflowError" class="hidden text-center py-8">
                        <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid fa-triangle-exclamation text-red-500 text-2xl"></i>
                        </div>
                        <p class="text-sm text-red-600">خطا در بارگذاری فرایندها</p>
                    </div>
                </div>

                {{-- Step 2: Task Details --}}
                <div id="taskStep2" class="hidden space-y-4">

                    {{-- Selected Workflow Badge --}}
                    <div id="selectedWorkflowBadge" class="flex items-center gap-2 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl">
                        <i class="fa-solid fa-diagram-project text-slate-700"></i>
                        <span class="text-sm font-medium text-slate-900" id="selectedWorkflowName"></span>
                        <button type="button" id="changeWorkflowBtn" class="mr-auto text-slate-700 hover:text-slate-900 text-sm font-medium">
                            <i class="fa-solid fa-pen text-xs ml-1"></i>
                            تغییر
                        </button>
                    </div>

                    <input type="hidden" name="workflow_id" id="selectedWorkflowId">

                    {{-- Title --}}
                    <div>
                        <div class="border border-gray-200 rounded-xl overflow-hidden focus-within:border-slate-900 focus-within:ring-2 focus-within:ring-slate-900/10 transition-all duration-200">
                            <div class="flex items-stretch">
                                <label for="task_title" class="bg-slate-50 border-l border-gray-200 px-4 py-3.5 text-sm text-slate-600 flex items-center leading-normal min-w-[120px]">
                                    عنوان کار
                                    <span class="text-red-500 mr-1">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="title"
                                    id="task_title"
                                    placeholder="عنوان کار را وارد کنید..."
                                    required
                                    class="flex-1 px-4 py-3.5 text-sm text-slate-900 outline-none bg-transparent leading-normal"
                                />
                            </div>
                        </div>
                    </div>

                    {{-- Assignee --}}
                    <div class="ts-field-wrapper">
                        <label for="assignee" class="ts-field-label min-w-[120px]">
                            مسئول انجام
                            <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="assignee"
                            id="task_assignee"
                            data-tom-select-ajax
                            data-url="{{ route('api.v1.client.org.users.keyValList', ['per_page' => 100, 'field' => 'full_name', 'filter' => ['user_type' => 2]]) }}"
                            data-placeholder="جستجو و انتخاب مسئول"
                            data-value-field="id"
                            data-label-field="label"
                            data-path="result"
                            data-template="keyValList"
                            required
                        ></select>
                    </div>

                    {{-- Description --}}
                    <div>
                        <div class="border border-gray-200 rounded-xl overflow-hidden focus-within:border-slate-900 focus-within:ring-2 focus-within:ring-slate-900/10 transition-all duration-200">
                            <div class="flex items-stretch">
                                <label for="task_description" class="bg-slate-50 border-l border-gray-200 px-4 py-3.5 text-sm text-slate-600 flex items-start leading-normal min-w-[120px]">
                                    توضیحات
                                </label>
                                <textarea
                                    name="description"
                                    id="task_description"
                                    rows="3"
                                    placeholder="توضیحات بیشتر درباره کار..."
                                    class="flex-1 px-4 py-3.5 text-sm text-slate-900 outline-none bg-transparent leading-normal resize-none"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Priority and Due Date --}}
                    <div class="grid grid-cols-2 gap-3">
                        {{-- Priority --}}
                        <div>
                            <div class="border border-gray-200 rounded-xl overflow-hidden focus-within:border-slate-900 focus-within:ring-2 focus-within:ring-slate-900/10 transition-all duration-200">
                                <div class="flex flex-col">
                                    <label for="task_priority" class="bg-slate-50 border-b border-gray-200 px-4 py-2 text-xs text-slate-600 leading-normal">
                                        اولویت
                                    </label>
                                    <select
                                        name="priority"
                                        id="task_priority"
                                        class="px-4 py-3 text-sm text-slate-900 outline-none bg-transparent"
                                    >
                                        @foreach($priorityOptions as $value => $label)
                                            <option value="{{ $value }}" {{ $value == 1 ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Due Date --}}
                        <div>
                            <div class="border border-gray-200 rounded-xl overflow-hidden focus-within:border-slate-900 focus-within:ring-2 focus-within:ring-slate-900/10 transition-all duration-200">
                                <div class="flex flex-col">
                                    <label for="task_due_date" class="bg-slate-50 border-b border-gray-200 px-4 py-2 text-xs text-slate-600 leading-normal">
                                        ددلاین
                                    </label>
                                    <input
                                        type="text"
                                        name="due_date"
                                        id="task_due_date"
                                        data-datetimepicker
                                        data-datepicker-format="YYYY-0M-0D 0h:0m"
                                        data-datepicker-gregorian="true"
                                        data-datepicker-time="true"
                                        placeholder="انتخاب تاریخ"
                                        readonly
                                        class="px-4 py-3 text-sm text-slate-900 outline-none bg-transparent cursor-pointer"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Estimated Hours --}}
                    <div>
                        <div class="border border-gray-200 rounded-xl overflow-hidden focus-within:border-slate-900 focus-within:ring-2 focus-within:ring-slate-900/10 transition-all duration-200">
                            <div class="flex items-stretch">
                                <label for="task_estimated_hours" class="bg-slate-50 border-l border-gray-200 px-4 py-3.5 text-sm text-slate-600 flex items-center leading-normal min-w-[120px]">
                                    زمان تخمینی (ساعت)
                                </label>
                                <input
                                    type="number"
                                    name="estimated_hours"
                                    id="task_estimated_hours"
                                    placeholder="مثلاً: 2"
                                    min="0"
                                    step="0.5"
                                    class="flex-1 px-4 py-3.5 text-sm text-slate-900 outline-none bg-transparent leading-normal"
                                />
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        <!-- Modal Footer -->
        <div id="taskModalFooter" class="hidden sticky bottom-0 bg-white border-t border-gray-100 px-6 py-4">
            <div class="grid grid-cols-2 gap-3">
                <button type="button" data-modal-close class="h-12 bg-gray-100 hover:bg-gray-200 text-slate-700 rounded-xl font-medium transition-all active:scale-[0.98]">
                    انصراف
                </button>
                <button type="submit" form="createTaskForm" id="submitTaskBtn" class="h-12 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-medium transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    <i class="fa-solid fa-check"></i>
                    <span id="submitTaskBtnText">ایجاد کار</span>
                </button>
            </div>
        </div>

    </div>
</div>

<style>
    #createTaskModal > div {
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from {
            transform: translateY(100%);
        }
        to {
            transform: translateY(0);
        }
    }

    @media (min-width: 640px) {
        #createTaskModal > div {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    }
</style>
