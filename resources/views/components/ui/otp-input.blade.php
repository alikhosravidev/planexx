@props([
    'length' => 4,
])

<div class="flex items-center justify-center gap-3" dir="ltr" {{ $attributes }}>
    @for($i = 0; $i < $length; $i++)
    <input 
        type="text" 
        maxlength="1" 
        autocomplete="off"
        class="otp-input w-16 h-16 text-center text-2xl font-bold text-text-primary bg-bg-secondary border-2 border-border-medium rounded-xl outline-none focus:border-primary focus:bg-bg-primary focus:shadow-focus transition-all duration-200"
        data-index="{{ $i }}"
        inputmode="numeric"
        pattern="[0-9]">
    @endfor
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.otp-input');
    
    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            const value = e.target.value.replace(/\D/g, '');
            e.target.value = value;
            
            if (value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            
            checkOTPComplete();
        });
        
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                inputs[index - 1].focus();
            }
        });
        
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text').replace(/\D/g, '');
            
            for (let i = 0; i < Math.min(pastedData.length, inputs.length); i++) {
                inputs[i].value = pastedData[i];
            }
            
            checkOTPComplete();
        });
    });
    
    function checkOTPComplete() {
        const otp = Array.from(inputs).map(input => input.value).join('');
        
        if (otp.length === inputs.length) {
            const event = new CustomEvent('otp-complete', { detail: { otp } });
            document.dispatchEvent(event);
        }
    }
});
</script>
@endpush
