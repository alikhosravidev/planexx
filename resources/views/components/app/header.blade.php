@props([
    'title' => '',
    'subtitle' => '',
    'icon' => 'fa-home',
    'gradient' => 'from-teal-600 to-teal-800',
])

<div class="bg-gradient-to-br {{ $gradient }} px-5 pt-8 pb-12 rounded-b-[32px] shadow-lg">
    <div class="text-center mb-6">
        <div class="w-16 h-16 mx-auto mb-4 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center">
            <i class="fa-solid {{ $icon }} text-white text-3xl"></i>
        </div>
        <h1 class="text-white text-2xl font-bold leading-tight mb-2">
            {{ $title }}
        </h1>
        @if($subtitle)
            <p class="text-white/80 text-sm leading-relaxed">
                {{ $subtitle }}
            </p>
        @endif
    </div>
    
    {{ $slot }}
</div>
