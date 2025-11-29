<x-layouts.app title="داشبورد">
    <div class="flex min-h-screen">
        <x-dashboard.sidebar current-page="dashboard" />
        
        <main class="flex-1 flex flex-col">
            <x-dashboard.header 
                title="داشبورد" 
                :breadcrumbs="$breadcrumbs" 
            />
            
            <div class="flex-1 p-6 lg:p-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
                    @foreach ($stats as $stat)
                        <x-dashboard.stat-card
                            :title="$stat['title']"
                            :value="$stat['value']"
                            :change="$stat['change'] ?? null"
                            :change-type="$stat['changeType'] ?? 'neutral'"
                            :icon="$stat['icon']"
                            :color="$stat['color']"
                        />
                    @endforeach
                </div>
                
                <!-- Quick Access Modules -->
                <x-dashboard.quick-access :modules="$quickAccessModules" />
            </div>
        </main>
    </div>
</x-layouts.app>
