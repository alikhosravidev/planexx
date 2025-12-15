@php
    $title = $pageTitle ?? 'ساختار سازمانی';
@endphp

<x-panel::layouts.app :title="$title">
    <div class="flex min-h-screen">
        <x-panel::dashboard.sidebar
            name="org.sidebar"
            :current-page="$currentPage"
            header-variant="back"
            module-title="ساختار سازمانی"
            module-icon="fa-solid fa-sitemap"
        />

        <main class="flex-1 flex flex-col min-w-0">
            <x-panel::dashboard.header :title="$title" :breadcrumbs="$breadcrumbs" />

            <div class="flex-1 p-6 lg:p-8">
                <x-panel::dashboard.stats :items="$stats" />

                <x-panel::dashboard.quick-access :modules="$quickAccessModules" cols="grid-cols-2 sm:grid-cols-2 lg:grid-cols-4" />

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    <x-panel::dashboard.distribution :items="$distribution" />

                    <x-panel::dashboard.activities :items="$activities" />

                </div>
            </div>
        </main>
    </div>
</x-panel::layouts.app>
