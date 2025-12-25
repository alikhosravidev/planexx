@php
    $pageTitle = isset($task) ? 'ویرایش کار' : 'افزودن کار جدید';
    $listTitle = 'کارها و وظایف';
    $listUrl = route('web.bpms.tasks.index');
    $currentPage = 'bpms-tasks';

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'مدیریت وظایف', 'url' => route('web.bpms.dashboard')],
        ['label' => $listTitle, 'url' => $listUrl],
        ['label' => $pageTitle],
    ];

    $actionButtons = [
        ['label' => 'بازگشت', 'url' => $listUrl, 'icon' => 'fa-solid fa-arrow-right', 'type' => 'outline'],
    ];

    $priorityOptions = [
        0 => 'کم',
        1 => 'متوسط',
        2 => 'بالا',
        3 => 'فوری',
    ];
@endphp

<x-panel::layouts.app :title="$pageTitle">
    <div class="flex min-h-screen">
        <x-panel::dashboard.sidebar
            name="bpms.sidebar"
            :current-page="$currentPage"
            header-variant="back"
            module-title="مدیریت وظایف"
            module-icon="fa-solid fa-diagram-project"
            color="indigo"
        />

        <main class="flex-1 flex flex-col min-w-0">
            <x-panel::dashboard.header
                :title="$pageTitle"
                :breadcrumbs="$breadcrumbs"
                :actions="$actionButtons"
            />

            <div class="flex-1 p-6 lg:p-8">
                <form data-ajax
                      data-method="{{ isset($task) ? 'PUT' : 'POST' }}"
                      action="{{ isset($task) ? route('api.v1.admin.bpms.tasks.update', ['task' => $task['id'] ?? null]) : route('api.v1.admin.bpms.tasks.store') }}"
                      data-on-success="redirect"
                      data-redirect-url="{{ $listUrl }}">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2 space-y-6">
                            {{-- Basic Info Card --}}
                            <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
                                <div class="px-6 py-4 border-b border-border-light">
                                    <h2 class="text-lg font-semibold text-text-primary leading-snug">اطلاعات کار</h2>
                                </div>
                                <div class="p-6 space-y-6">
                                    <x-panel::forms.input
                                        name="title"
                                        label="عنوان کار"
                                        placeholder="عنوان کار را وارد کنید..."
                                        :value="$task['title'] ?? old('title')"
                                        required
                                    />

                                    <x-panel::forms.textarea
                                        name="description"
                                        label="توضیحات"
                                        placeholder="توضیحات کار را وارد کنید..."
                                        :value="$task['description']['full'] ?? old('description')"
                                        rows="4"
                                    />

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <x-panel::forms.tom-select-ajax
                                            name="workflow_id"
                                            label="فرایند"
                                            :url="route('api.v1.admin.bpms.workflows.keyValList', ['per_page' => 100, 'field' => 'name'])"
                                            template="keyValList"
                                            :value="$task['workflow_id'] ?? $workflowId ?? old('workflow_id')"
                                            placeholder="انتخاب فرایند..."
                                            required
                                        />

                                        <x-panel::forms.tom-select-ajax
                                            name="assignee_id"
                                            label="مسئول"
                                            :url="route('api.v1.admin.org.users.keyValList', ['per_page' => 100, 'field' => 'full_name'])"
                                            template="keyValList"
                                            :value="$task['assignee_id'] ?? old('assignee_id')"
                                            placeholder="انتخاب مسئول..."
                                            required
                                        />
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <x-panel::forms.tom-select
                                            name="priority"
                                            label="اولویت"
                                            :options="$priorityOptions"
                                            :value="$task['priority']['value'] ?? old('priority', 1)"
                                            required
                                        />

                                        <x-panel::forms.date
                                            name="due_date"
                                            label="ددلاین"
                                            :value="$task['due_date']['main'] ?? old('due_date')"
                                            placeholder="انتخاب تاریخ..."
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="lg:sticky lg:top-6 space-y-6 self-start">
                            {{-- Actions Card --}}
                            <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
                                <div class="px-6 py-4 border-b border-border-light">
                                    <h2 class="text-lg font-semibold text-text-primary leading-snug">عملیات</h2>
                                </div>
                                <div class="p-6 space-y-4">
                                    <x-panel::ui.button type="submit" class="w-full" icon="fa-solid fa-save">
                                        {{ isset($task) ? 'ذخیره تغییرات' : 'افزودن کار' }}
                                    </x-panel::ui.button>

                                    <x-panel::ui.button
                                        type="button"
                                        variant="outline"
                                        class="w-full"
                                        icon="fa-solid fa-arrow-right"
                                        onclick="window.location.href='{{ $listUrl }}'"
                                    >
                                        انصراف
                                    </x-panel::ui.button>
                                </div>
                            </div>

                            @if(isset($task))
                                {{-- Task Info Card --}}
                                <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
                                    <div class="px-6 py-4 border-b border-border-light">
                                        <h2 class="text-lg font-semibold text-text-primary leading-snug">اطلاعات بیشتر</h2>
                                    </div>
                                    <div class="p-6 space-y-4 text-sm">
                                        @if(isset($task['slug']))
                                            <div class="flex justify-between">
                                                <span class="text-text-muted">کد کار:</span>
                                                <span class="font-mono text-text-primary">{{ $task['slug'] }}</span>
                                            </div>
                                        @endif
                                        @if(isset($task['created_at']))
                                            <div class="flex justify-between">
                                                <span class="text-text-muted">تاریخ ایجاد:</span>
                                                <span class="text-text-primary" dir="ltr">{{ $task['created_at']['human']['short'] }}</span>
                                            </div>
                                        @endif
                                        @if(isset($task['creator']))
                                            <div class="flex justify-between">
                                                <span class="text-text-muted">ایجاد کننده:</span>
                                                <span class="text-text-primary">{{ $task['creator']['full_name'] ?? '-' }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
</x-panel::layouts.app>
