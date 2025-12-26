@props([
    'placeholder' => 'جستجو کنید...',
    'withVoice' => true,
])

<div class="bg-white rounded-2xl p-3 shadow-xl" {{ $attributes }}>
    <div class="flex items-center gap-2">
        
        <div class="flex-1 flex items-center">
            <i class="fa-solid fa-search text-text-muted text-base mr-3"></i>
            <input 
                type="text" 
                id="searchInput"
                class="flex-1 py-3 ps-2 text-base text-text-primary outline-none bg-transparent leading-normal placeholder:text-text-muted"
                placeholder="{{ $placeholder }}"
                autocomplete="off">
        </div>
        
        @if($withVoice)
        <button 
            id="voiceBtn"
            class="w-11 h-11 flex items-center justify-center text-text-muted hover:text-teal-600 hover:bg-teal-50 rounded-xl transition-all duration-200"
            title="جستجوی صوتی">
            <i class="fa-solid fa-microphone text-lg"></i>
        </button>
        @endif
        
    </div>
</div>

@if($withVoice)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const voiceBtn = document.getElementById('voiceBtn');
    const searchInput = document.getElementById('searchInput');
    
    if (voiceBtn && searchInput && 'webkitSpeechRecognition' in window) {
        const recognition = new webkitSpeechRecognition();
        recognition.lang = 'fa-IR';
        recognition.continuous = false;
        
        voiceBtn.addEventListener('click', () => {
            recognition.start();
            voiceBtn.classList.add('text-red-500');
        });
        
        recognition.onresult = (event) => {
            const transcript = event.results[0][0].transcript;
            searchInput.value = transcript;
            searchInput.dispatchEvent(new Event('input'));
        };
        
        recognition.onend = () => {
            voiceBtn.classList.remove('text-red-500');
        };
    }
});
</script>
@endpush
@endif
