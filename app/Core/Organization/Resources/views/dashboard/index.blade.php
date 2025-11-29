@php
    $title = $pageTitle ?? 'ساختار سازمانی';
@endphp

<x-layouts.app :title="$title">
    <div class="flex min-h-screen">
        <x-dashboard.sidebar
            menu-name="org.sidebar"
            :current-page="$currentPage"
            header-variant="back"
            module-title="ساختار سازمانی"
            module-icon="fa-solid fa-sitemap"
        />

        <main class="flex-1 flex flex-col">
            <x-dashboard.module-header :page-title="$title" :breadcrumbs="$breadcrumbs" />

            <div class="flex-1 p-6 lg:p-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
                    @foreach($stats as $s)
                        <x-dashboard.stat-card
                            :title="$s['title']"
                            :value="$s['value']"
                            :change="$s['change']"
                            :change-type="$s['changeType']"
                            :icon="$s['icon']"
                            :color="$s['color']"
                        />
                    @endforeach
                </div>

                <x-dashboard.quick-access :modules="$quickAccessModules" cols="grid-cols-2 sm:grid-cols-2 lg:grid-cols-4" />

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    <div class="bg-white border border-border-light rounded-2xl p-6">
                        <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">توزیع بر اساس نوع کاربر</h2>
                        <div class="space-y-4">
                            @foreach($distribution as $row)
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm text-text-secondary leading-normal">{{ $row['label'] }}</span>
                                        <span class="text-sm font-semibold text-text-primary leading-normal">{{ $row['value'] }}</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2.5">
                                        <div class="{{ $row['color'] }} h-2.5 rounded-full" style="width: {{ $row['percent'] }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white border border-border-light rounded-2xl p-6">
                        <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">آخرین فعالیت‌ها</h2>
                        <div class="space-y-4">
                            @foreach($activities as $act)
                                <div class="flex items-start gap-3 pb-4 border-b border-border-light last:border-0 last:pb-0">
                                    <div class="w-8 h-8 {{ $act['icon_bg'] }} rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="{{ $act['icon'] }} text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-text-primary leading-normal mb-1">{{ $act['title'] }}</p>
                                        <p class="text-xs text-text-muted leading-normal">{{ $act['desc'] }}</p>
                                    </div>
                                    <span class="text-xs text-text-muted leading-normal">{{ $act['time'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</x-layouts.app>
