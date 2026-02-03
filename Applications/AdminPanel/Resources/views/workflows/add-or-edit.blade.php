@php
    $pageTitle = isset($workflow) ? 'ویرایش فرایند' : 'ایجاد فرایند جدید';
    $listTitle = 'مدیریت فرایندها';
    $listUrl = route('web.bpms.workflows.index');
    $currentPage = 'bpms-workflows';

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'مدیریت وظایف', 'url' => route('web.bpms.dashboard')],
        ['label' => $listTitle, 'url' => $listUrl],
        ['label' => $pageTitle],
    ];

    $actionButtons = [
        ['label' => 'بازگشت', 'url' => $listUrl, 'icon' => 'fa-solid fa-arrow-right', 'type' => 'outline'],
    ];

    $isActive = ! isset($workflow) || ! empty($workflow['is_active']);

    $initialStates = $workflow['states'] ?? [];

    $selectedRoleIds = collect($workflow['allowedRoles'] ?? [])->pluck('id')->toArray();
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
                      data-initial-states='@json($initialStates)'
                      data-method="{{ isset($workflow) ? 'PUT' : 'POST' }}"
                      action="{{ isset($workflow) ? route('api.v1.admin.bpms.workflows.update', ['workflow' => $workflow['id'] ?? null]) : route('api.v1.admin.bpms.workflows.store') }}"
                      data-on-success="redirect">
                    @csrf
                    <input type="hidden" name="is_active" id="isActiveHidden" value="{{ $isActive ? '1' : '0' }}">

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2 space-y-6">
                            @include('panel::workflows.partials.basic-info')
                            @include('panel::workflows.partials.states')
                        </div>

                        <div class="lg:sticky lg:top-6 space-y-6 self-start">
                            @include('panel::workflows.partials.preview')
                            @include('panel::workflows.partials.actions')
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>

    @include('panel::workflows.partials.state-template')

    @vite('Applications/AdminPanel/Resources/js/pages/bpms-workflows.js')
</x-panel::layouts.app>
