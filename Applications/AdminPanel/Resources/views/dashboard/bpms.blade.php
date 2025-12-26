@php
    $title = $pageTitle ?? 'مدیریت وظایف';
@endphp

<x-panel::layouts.app :title="$title">
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
            <x-panel::dashboard.header :title="$title" :breadcrumbs="$breadcrumbs" />

            <div class="flex-1 p-6 lg:p-8">
                <x-panel::dashboard.stats :items="$stats" />

                <x-panel::dashboard.quick-access :modules="$quickAccessModules" cols="grid-cols-2 sm:grid-cols-2 lg:grid-cols-4" />

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                    <x-panel::bpms.top-workflows :items="$topWorkflows" class="lg:col-span-2" />

                    <x-panel::dashboard.distribution :items="$taskDistribution" title="وضعیت کارها" />
                </div>
            </div>
        </main>
    </div>
</x-panel::layouts.app>
