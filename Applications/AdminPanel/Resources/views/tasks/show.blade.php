@php
    $pageTitle = 'جزئیات کار';
    $listTitle = 'کارها و وظایف';
    $listUrl = route('web.bpms.tasks.index');
    $currentPage = 'bpms-tasks';

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'مدیریت وظایف', 'url' => route('web.bpms.dashboard')],
        ['label' => $listTitle, 'url' => $listUrl],
        ['label' => $pageTitle],
    ];

    $taskJson = json_encode($task, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
    $actionButtons = array_values(array_filter([
        isset($task['id']) ? [
            'label' => 'ویرایش کار',
            'url' => '#',
            'icon' => 'fa-solid fa-pen',
            'type' => 'secondary',
            'data_attrs' => [
                'onclick' => "openEditTaskModal({$taskJson})",
            ],
        ] : null,
    ]));

    $priority = $task['priority'] ?? [];
    $priorityName = $priority['name'] ?? 'MEDIUM';
    $priorityLabel = $priority['label'] ?? 'متوسط';

    $priorityStyles = [
        'LOW' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'gradient' => 'from-slate-500 to-slate-600'],
        'MEDIUM' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'gradient' => 'from-blue-500 to-blue-600'],
        'HIGH' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'gradient' => 'from-orange-500 to-orange-600'],
        'URGENT' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'gradient' => 'from-red-500 to-red-600'],
    ];

    $priorityStyle = $priorityStyles[$priorityName] ?? $priorityStyles[1];

    $workflowStates = $task['workflow']['states'] ?? [];
    $currentStateId = $task['current_state']['id'] ?? null;

    $progressPercentage = $task['progress_percentage'] ?? 0;
    $currentOrder = $task['current_state_order'] ?? 1;
    $totalStates = $task['total_states'] ?? 1;
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
                :title="$task['title'] ?? $pageTitle"
                :breadcrumbs="$breadcrumbs"
                :actions="$actionButtons"
            />

            <div class="flex-1 p-6 lg:p-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
                            <x-panel::bpms.workflow-stepper :states="$workflowStates" :current-state-id="$currentStateId" />
                            @include('panel::tasks.partials.header')
                        </div>
                        @include('panel::tasks.partials.deadline')
                        @include('panel::tasks.partials.attachments')
                        @include('panel::tasks.partials.history')
                    </div>

                    <div class="space-y-6">
                        @include('panel::tasks.partials.actions')
                        @include('panel::tasks.partials.info')
                        @include('panel::tasks.partials.watchers')
                        @include('panel::tasks.partials.workflow-info')
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('panel::tasks.modals.follow-up-modal', ['taskId' => $task['id'] ?? null])

    @php
        $currentStateIndex = 0;
        foreach ($workflowStates as $index => $state) {
            if (($state['id'] ?? null) == $currentStateId) {
                $currentStateIndex = $index;
                break;
            }
        }
        $nextState = $workflowStates[$currentStateIndex + 1] ?? null;
    @endphp

    @include('panel::tasks.modals.forward-modal', ['taskId' => $task['id'] ?? null, 'currentState' => $task['current_state'] ?? null, 'nextState' => $nextState])

    @include('panel::tasks.modals.form-task-modal')

    <x-panel::modals.attachment-modal module-name="bpms" :entity-type="$task['morph_class']" :entity-id="$task['id']"/>

    @vite('Applications/AdminPanel/Resources/js/pages/bpms-tasks.js')
</x-panel::layouts.app>
