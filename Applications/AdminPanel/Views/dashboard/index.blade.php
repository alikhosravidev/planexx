<x-panel::layouts.app title="داشبورد">
    <div class="flex min-h-screen">
        <x-panel::dashboard.sidebar current-page="dashboard" name="dashboard.sidebar" />

        <main class="flex-1 flex flex-col min-w-0">
            <x-panel::dashboard.header title="داشبورد" :breadcrumbs="$breadcrumbs" />

            <div class="flex-1 p-6 lg:p-8">
                <!-- Stats Cards -->
                <x-panel::dashboard.stats :items="$stats" />

                <!-- Quick Access Modules -->
                <x-panel::dashboard.quick-access :modules="$quickAccessModules" />
            </div>
        </main>
    </div>
</x-panel::layouts.app>
