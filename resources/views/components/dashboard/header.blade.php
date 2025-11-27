@props(['title' => 'داشبورد', 'breadcrumbs' => null])

<header class="bg-white border-b border-border-light px-6 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-text-primary">{{ $title }}</h1>

            @if($breadcrumbs)
                <nav class="flex items-center gap-2 text-sm mt-1">
                    @foreach($breadcrumbs as $index => $crumb)
                        @if($index > 0)
                            <i class="fa-solid fa-chevron-left text-gray-400 text-xs"></i>
                        @endif

                        @if(isset($crumb['url']) && $index < count($breadcrumbs) - 1)
                            <a href="{{ $crumb['url'] }}" class="text-text-secondary hover:text-text-primary">
                                {{ $crumb['label'] }}
                            </a>
                        @else
                            <span class="text-text-primary font-medium">{{ $crumb['label'] }}</span>
                        @endif
                    @endforeach
                </nav>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <div class="relative hidden md:block">
                <input
                    type="text"
                    placeholder="جستجو..."
                    class="w-64 px-4 py-2 pr-10 border border-border-light rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent"
                >
                <i class="fa-solid fa-search absolute right-3 top-1/2 -translate-y-1/2 text-text-muted"></i>
            </div>

            <button class="relative p-2 hover:bg-gray-100 rounded-lg">
                <i class="fa-solid fa-bell text-xl text-text-secondary"></i>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2 p-2 hover:bg-gray-100 rounded-lg">
                    <img src="{{ auth()->user()->avatar ?? '/images/default-avatar.png' }}"
                         alt="User"
                         class="w-8 h-8 rounded-full">
                    <span class="hidden md:block text-sm font-medium">{{ auth()->user()->full_name ?? 'کاربر' }}</span>
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>

                <div x-show="open"
                     @click.away="open = false"
                     x-transition
                     class="absolute left-0 mt-2 w-48 bg-white border border-border-light rounded-xl shadow-lg py-2 z-50">
                    <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-50">
                        <i class="fa-solid fa-user ml-2"></i>
                        پروفایل
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-50">
                        <i class="fa-solid fa-cog ml-2"></i>
                        تنظیمات
                    </a>
                    <hr class="my-2">
                    <form method="POST" action="">
                        @csrf
                        <button type="submit" class="w-full text-right px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                            <i class="fa-solid fa-sign-out ml-2"></i>
                            خروج
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
