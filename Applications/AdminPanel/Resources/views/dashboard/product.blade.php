@php
    $title = $pageTitle ?? 'محصولات و لیست‌ها';
@endphp

<x-panel::layouts.app :title="$title">
    <div class="flex min-h-screen">
        <x-panel::dashboard.sidebar
            name="product.sidebar"
            :current-page="$currentPage"
            header-variant="back"
            module-title="محصولات و لیست‌ها"
            module-icon="fa-solid fa-sitemap"
        />

        <main class="flex-1 flex flex-col min-w-0">
            <x-panel::dashboard.header :title="$title" :breadcrumbs="$breadcrumbs" />

            <div class="flex-1 p-6 lg:p-8">
                <x-panel::dashboard.stats :items="$stats" />

                <x-panel::dashboard.quick-access :modules="$quickAccessModules" cols="grid-cols-2 sm:grid-cols-2 lg:grid-cols-4" />
            </div>
        </main>
    </div>
</x-panel::layouts.app>
