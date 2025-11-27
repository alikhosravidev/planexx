<x-layouts.app :title="$title ?? 'ورود'">
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 to-slate-700 p-4">
        <div class="w-full max-w-md">
            {{ $slot }}
        </div>
    </div>
</x-layouts.app>
