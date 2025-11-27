@props(['currentPage' => 'dashboard'])

<aside class="w-64 bg-white border-l border-border-light flex flex-col">
    <div class="p-6 border-b border-border-light">
        <a href="#" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-layer-group text-white text-xl"></i>
            </div>
            <span class="text-xl font-bold text-text-primary">{{ config('app.name') }}</span>
        </a>
    </div>
    
    <nav class="flex-1 p-4 overflow-y-auto">
        <div class="space-y-1">
            <a href="#" 
               @class([
                   'flex items-center gap-3 px-4 py-3 rounded-xl transition-colors',
                   'bg-primary text-white' => $currentPage === 'dashboard',
                   'text-text-secondary hover:bg-gray-50' => $currentPage !== 'dashboard',
               ])>
                <i class="fa-solid fa-chart-line text-lg"></i>
                <span class="font-medium">داشبورد</span>
            </a>
            
            <a href="#" 
               @class([
                   'flex items-center gap-3 px-4 py-3 rounded-xl transition-colors',
                   'bg-primary text-white' => $currentPage === 'organization',
                   'text-text-secondary hover:bg-gray-50' => $currentPage !== 'organization',
               ])>
                <i class="fa-solid fa-sitemap text-lg"></i>
                <span class="font-medium">ساختار سازمانی</span>
            </a>
            
            <a href="#" 
               @class([
                   'flex items-center gap-3 px-4 py-3 rounded-xl transition-colors',
                   'bg-primary text-white' => $currentPage === 'knowledge',
                   'text-text-secondary hover:bg-gray-50' => $currentPage !== 'knowledge',
               ])>
                <i class="fa-solid fa-book text-lg"></i>
                <span class="font-medium">پایگاه تجربه</span>
            </a>
            
            <a href="#" 
               @class([
                   'flex items-center gap-3 px-4 py-3 rounded-xl transition-colors',
                   'bg-primary text-white' => $currentPage === 'documents',
                   'text-text-secondary hover:bg-gray-50' => $currentPage !== 'documents',
               ])>
                <i class="fa-solid fa-folder text-lg"></i>
                <span class="font-medium">مدیریت اسناد</span>
            </a>
        </div>
    </nav>
    
    <div class="p-4 border-t border-border-light">
        <div class="flex items-center gap-3">
            <img src="{{ auth()->user()->avatar ?? '/images/default-avatar.png' }}" 
                 alt="User" 
                 class="w-10 h-10 rounded-full">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-text-primary truncate">
                    {{ auth()->user()->full_name ?? 'کاربر' }}
                </p>
                <p class="text-xs text-text-secondary truncate">
                    {{ auth()->user()->job_position?->title ?? 'موقعیت شغلی' }}
                </p>
            </div>
        </div>
    </div>
</aside>
