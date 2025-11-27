<?php
$pageTitle = 'ورود به سیستم';
require_once __DIR__ . '/_components/head.php';
?>

<body class="bg-bg-secondary min-h-screen flex items-center justify-center p-4">
  
  <!-- Auth Container -->
  <div class="w-full max-w-md">
    
    <!-- Logo & Title -->
    <div class="text-center mb-5xl">
      <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-primary to-gray-700 rounded-3xl mb-4 shadow-lg">
        <i class="fa-solid fa-layer-group text-3xl text-white"></i>
      </div>
      <h1 class="text-3xl font-bold text-text-primary mb-2 leading-tight">
        ورود به پلنکس
      </h1>
      <p class="text-base text-text-secondary leading-normal">
        لطفاً شماره موبایل خود را وارد کنید
      </p>
    </div>
    
    <!-- Back Button (Outside Card) -->
    <div id="back-button-container" class="hidden mb-3 pr-5">
      <button id="back-button" 
              class="flex items-center gap-1.5 text-text-muted hover:text-text-primary transition-all duration-200 text-sm leading-normal">
        <i class="fa-solid fa-arrow-right text-xs"></i>
        <span>بازگشت</span>
      </button>
    </div>
    
    <!-- Auth Card -->
    <div class="bg-bg-primary border border-border-light rounded-3xl shadow-lg overflow-hidden">
      
      <!-- Step 1: Mobile Input -->
      <div id="step-mobile" class="p-10 md:p-5xl">
        <form id="mobile-form" data-validate>
          
          <!-- Mobile Input -->
          <div class="mb-3xl">
            <input type="tel" 
                   id="mobile-input"
                   data-validate="mobile"
                   required
                   maxlength="11"
                   placeholder="۰۹۱۲۳۴۵۶۷۸۹"
                   autocomplete="off"
                   class="w-full text-center text-2xl font-semibold text-text-primary bg-transparent border-2 border-border-medium rounded-2xl px-6 py-4 outline-none focus:border-primary focus:shadow-focus transition-all duration-200 leading-normal"
                   dir="ltr">
            <p class="text-sm text-text-muted mt-2 text-center leading-normal">
              شماره موبایل خود را با ۰۹ وارد کنید
            </p>
          </div>
          
          <!-- Submit Button -->
          <button type="submit" 
                  class="w-full bg-primary text-white text-base font-medium rounded-xl py-4 hover:bg-gray-800 hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 flex items-center justify-center gap-2 leading-normal">
            <span>دریافت کد تایید</span>
            <i class="fa-solid fa-arrow-left"></i>
          </button>
          
        </form>
      </div>
      
      <!-- Step 2: OTP Input -->
      <div id="step-otp" class="p-10 md:p-5xl hidden">
        
        <form id="otp-form">
          
          <!-- Info Text -->
          <div class="text-center mb-4">
            <p class="text-base text-text-secondary leading-relaxed">
              کد تایید ۴ رقمی به شماره <strong id="mobile-display" class="text-text-primary" dir="ltr">09123456789</strong> ارسال شد
            </p>
          </div>
          
          <!-- OTP Inputs -->
          <div class="flex items-center justify-center gap-3 mb-3xl" dir="ltr">
            <input type="text" 
                   maxlength="1" 
                   autocomplete="off"
                   class="otp-input w-16 h-16 text-center text-2xl font-bold text-text-primary bg-bg-secondary border-2 border-border-medium rounded-xl outline-none focus:border-primary focus:bg-bg-primary focus:shadow-focus transition-all duration-200"
                   data-index="0"
                   inputmode="numeric"
                   pattern="[0-9]">
            <input type="text" 
                   maxlength="1" 
                   autocomplete="off"
                   class="otp-input w-16 h-16 text-center text-2xl font-bold text-text-primary bg-bg-secondary border-2 border-border-medium rounded-xl outline-none focus:border-primary focus:bg-bg-primary focus:shadow-focus transition-all duration-200"
                   data-index="1"
                   inputmode="numeric"
                   pattern="[0-9]">
            <input type="text" 
                   maxlength="1" 
                   autocomplete="off"
                   class="otp-input w-16 h-16 text-center text-2xl font-bold text-text-primary bg-bg-secondary border-2 border-border-medium rounded-xl outline-none focus:border-primary focus:bg-bg-primary focus:shadow-focus transition-all duration-200"
                   data-index="2"
                   inputmode="numeric"
                   pattern="[0-9]">
            <input type="text" 
                   maxlength="1" 
                   autocomplete="off"
                   class="otp-input w-16 h-16 text-center text-2xl font-bold text-text-primary bg-bg-secondary border-2 border-border-medium rounded-xl outline-none focus:border-primary focus:bg-bg-primary focus:shadow-focus transition-all duration-200"
                   data-index="3"
                   inputmode="numeric"
                   pattern="[0-9]">
          </div>
          
          <!-- Resend Timer -->
          <div class="text-center mb-3xl">
            <p class="text-sm text-text-muted leading-normal">
              کد را دریافت نکردید؟
            </p>
            <button type="button" 
                    id="resend-button" 
                    disabled
                    class="text-sm font-medium text-text-muted mt-1 leading-normal disabled:opacity-50">
              ارسال مجدد (<span id="timer">60</span>)
            </button>
          </div>
          
          <!-- Submit Button (Hidden - Auto Submit) -->
          <button type="submit" 
                  id="otp-submit"
                  class="w-full bg-primary text-white text-base font-medium rounded-xl py-4 hover:bg-gray-800 hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 leading-normal">
            ورود به سیستم
          </button>
          
        </form>
        
      </div>
      
    </div>
    
    <!-- Info -->
    <p class="text-center text-sm text-text-muted mt-4 leading-normal">
      با ورود به سیستم، 
      <a href="#" class="text-primary hover:underline">قوانین و مقررات</a>
      را می‌پذیرید
    </p>
    
  </div>
  
  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>
  
  <script>
    // Auth Logic
    const AuthApp = {
      
      init() {
        this.initMobileForm();
        this.initOTPInputs();
        this.initBackButton();
        this.initResendButton();
        this.initMobileKeyboardFix();
      },
      
      // Fix Mobile Keyboard Overlap
      initMobileKeyboardFix() {
        const inputs = document.querySelectorAll('#mobile-input, .otp-input');
        
        inputs.forEach(input => {
          input.addEventListener('focus', () => {
            setTimeout(() => {
              input.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 300);
          });
        });
      },
      
      // Mobile Form Handler
      initMobileForm() {
        const form = document.getElementById('mobile-form');
        const mobileInput = document.getElementById('mobile-input');
        
        // فرمت خودکار شماره موبایل
        mobileInput.addEventListener('input', (e) => {
          let value = e.target.value.replace(/\D/g, '');
          e.target.value = value;
        });
        
        form.addEventListener('submit', (e) => {
          e.preventDefault();
          
          const mobile = mobileInput.value;
          
          if (!Utils.validateMobile(mobile)) {
            Utils.showToast('شماره موبایل را به درستی وارد کنید', 'error');
            mobileInput.classList.add('border-red-500');
            return;
          }
          
          mobileInput.classList.remove('border-red-500');
          
          // نمایش Step 2
          this.showOTPStep(mobile);
        });
      },
      
      // Show OTP Step
      showOTPStep(mobile) {
        document.getElementById('step-mobile').classList.add('hidden');
        document.getElementById('step-otp').classList.remove('hidden');
        document.getElementById('back-button-container').classList.remove('hidden');
        document.getElementById('mobile-display').textContent = mobile;
        
        // فوکوس روی اولین input
        setTimeout(() => {
          document.querySelector('.otp-input[data-index="0"]').focus();
        }, 100);
        
        // شروع تایمر
        this.startResendTimer();
        
        Utils.showToast('کد تایید ارسال شد', 'success');
      },
      
      // OTP Inputs Handler
      initOTPInputs() {
        const inputs = document.querySelectorAll('.otp-input');
        
        inputs.forEach((input, index) => {
          // فقط اعداد
          input.addEventListener('input', (e) => {
            const value = e.target.value.replace(/\D/g, '');
            e.target.value = value;
            
            // اگر مقدار وارد شد، به input بعدی برو
            if (value && index < inputs.length - 1) {
              inputs[index + 1].focus();
            }
            
            // چک کردن اگر همه پر شدند
            this.checkOTPComplete();
          });
          
          // Backspace برای رفتن به input قبلی
          input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
              inputs[index - 1].focus();
            }
          });
          
          // Paste Handler
          input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text').replace(/\D/g, '');
            
            for (let i = 0; i < Math.min(pastedData.length, inputs.length); i++) {
              inputs[i].value = pastedData[i];
            }
            
            this.checkOTPComplete();
          });
        });
      },
      
      // Check OTP Complete
      checkOTPComplete() {
        const inputs = document.querySelectorAll('.otp-input');
        const otp = Array.from(inputs).map(input => input.value).join('');
        
        if (otp.length === 4) {
          // Auto Submit
          setTimeout(() => {
            this.submitOTP(otp);
          }, 300);
        }
      },
      
      // Submit OTP
      submitOTP(otp) {
        // نمایش Toast اول
        const loadingToast = this.showCustomToast('در حال ورود به سیستم...', 'info');
        
        // شبیه‌سازی ورود
        setTimeout(() => {
          // حذف Toast قبلی
          if (loadingToast) loadingToast.remove();
          
          // نمایش Toast موفقیت
          Utils.showToast('ورود موفقیت‌آمیز!', 'success');
          
          // Redirect به داشبورد
          setTimeout(() => {
            window.location.href = '/dashboard/index.php';
          }, 1500);
        }, 1000);
      },
      
      // Custom Toast که reference برمی‌گرداند
      showCustomToast(message, type = 'info') {
        const colors = {
          success: 'bg-green-500',
          error: 'bg-red-500',
          warning: 'bg-yellow-500',
          info: 'bg-blue-500'
        };
        
        const toast = document.createElement('div');
        toast.className = `fixed top-4 left-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        return toast;
      },
      
      // Back Button
      initBackButton() {
        document.getElementById('back-button').addEventListener('click', () => {
          document.getElementById('step-otp').classList.add('hidden');
          document.getElementById('step-mobile').classList.remove('hidden');
          document.getElementById('back-button-container').classList.add('hidden');
          
          // پاک کردن OTP inputs و reset تایمر
          document.querySelectorAll('.otp-input').forEach(input => {
            input.value = '';
          });
          
          // فوکوس روی input موبایل
          setTimeout(() => {
            document.getElementById('mobile-input').focus();
          }, 100);
        });
      },
      
      // Resend Timer
      startResendTimer() {
        let seconds = 60;
        const timerElement = document.getElementById('timer');
        const resendButton = document.getElementById('resend-button');
        
        const interval = setInterval(() => {
          seconds--;
          timerElement.textContent = seconds;
          
          if (seconds <= 0) {
            clearInterval(interval);
            resendButton.disabled = false;
            resendButton.textContent = 'ارسال مجدد کد';
            resendButton.classList.remove('text-text-muted');
            resendButton.classList.add('text-primary', 'hover:underline');
          }
        }, 1000);
      },
      
      // Resend Button
      initResendButton() {
        document.getElementById('resend-button').addEventListener('click', () => {
          const mobile = document.getElementById('mobile-display').textContent;
          Utils.showToast('کد مجدداً ارسال شد', 'success');
          this.startResendTimer();
          
          document.getElementById('resend-button').disabled = true;
          document.getElementById('resend-button').classList.remove('text-primary', 'hover:underline');
          document.getElementById('resend-button').classList.add('text-text-muted');
        });
      }
      
    };
    
    // اجرا بعد از لود
    window.addEventListener('load', () => {
      AuthApp.init();
    });
  </script>
  
</body>
</html>

