<x-layouts.app title="داشبورد">
    <div class="flex min-h-screen">
        <x-dashboard.sidebar current-page="dashboard" name="dashboard.sidebar" />

        <main class="flex-1 flex flex-col">
            <x-dashboard.header title="داشبورد" :breadcrumbs="$breadcrumbs" />

            <div class="flex-1 p-6 lg:p-8">
                <!-- Stats Cards -->
                <x-dashboard.stats :items="$stats" />

                <!-- Quick Access Modules -->
                <x-dashboard.quick-access :modules="$quickAccessModules" />
            </div>
        </main>
    </div>
</x-layouts.app>
