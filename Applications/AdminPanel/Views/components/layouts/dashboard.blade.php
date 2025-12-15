<x-panel::layouts.app :title="$title ?? 'داشبورد'">
    <div class="flex min-h-screen">
        <x-panel::dashboard.sidebar name="dashboard.sidebar" :current-page="$currentPage ?? 'dashboard'" />

        <main class="flex-1 flex flex-col min-w-0">
            <x-panel::dashboard.header :title="$title ?? 'داشبورد'" />

            <div class="flex-1 p-6">
                {{ $slot }}
            </div>
        </main>
    </div>
</x-panel::layouts.app>
