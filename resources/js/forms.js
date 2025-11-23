/**
 * Form handling and validation for Planexx
 */
import { validation } from './validation.js';
import { notifications } from './notifications.js';
import { utilities } from './utilities.js';

export const forms = {

  /**
   * Initialize form validation
   */
  initFormValidation() {
    document.querySelectorAll('form[data-validate]').forEach(form => {
      form.addEventListener('submit', (e) => {
        e.preventDefault();

        if (this.validateForm(form)) {
          // Check if form has custom AJAX handling
          if (form.hasAttribute('data-route')) {
            this.handleAjaxSubmit(form);
          } else {
            form.submit();
          }
        } else {
          notifications.showError('لطفاً تمام فیلدها را به درستی پر کنید');
        }
      });
    });
  },

  /**
   * Handle AJAX form submission
   * @param {HTMLFormElement} form - Form element
   */
  async handleAjaxSubmit(form) {
    const route = form.getAttribute('data-route');
    const method = form.getAttribute('data-method') || 'post';
    const successAction = form.getAttribute('data-success-action');

    if (!route) return;

    try {
      const formData = new FormData(form);
      const data = Object.fromEntries(formData.entries());

      let response;
      if (method.toLowerCase() === 'get') {
        response = await axios.get(route, { params: data });
      } else {
        response = await axios[method.toLowerCase()](route, data);
      }

      if (response.data.status) {
        if (response.data.message) {
          notifications.showSuccess(response.data.message);
        }

        // Handle success actions
        if (successAction) {
          this.handleSuccessAction(successAction, response.data);
        }

        // Trigger custom event for additional handling
        form.dispatchEvent(new CustomEvent('ajax:success', {
          detail: { response: response.data, form }
        }));
      } else {
        notifications.showError(response.data.message || 'خطا در ارسال اطلاعات');
      }
    } catch (error) {
      console.error('Form submit error:', error);
      notifications.showError('خطا در اتصال به سرور');
    }
  },

  /**
   * Handle success actions based on data-success-action attribute
   * @param {string} action - Success action type
   * @param {object} responseData - Response data
   */
  handleSuccessAction(action, responseData) {
    switch (action) {
      case 'show-otp-step':
        this.showOTPStep(responseData);
        break;
      case 'redirect':
        if (responseData.redirect_url) {
          setTimeout(() => {
            window.location.href = responseData.redirect_url;
          }, 1000);
        }
        break;
      case 'login-success':
        this.handleLoginSuccess(responseData);
        break;
      default:
        console.log('Unknown success action:', action);
    }
  },

  /**
   * Show OTP input step
   * @param {object} data - Response data
   */
  showOTPStep(data) {
    const stepMobile = document.getElementById('step-mobile');
    const stepOtp = document.getElementById('step-otp');
    const backButton = document.getElementById('back-button-container');
    const mobileDisplay = document.getElementById('mobile-display');

    if (stepMobile && stepOtp) {
      stepMobile.classList.add('hidden');
      stepOtp.classList.remove('hidden');
    }

    if (backButton) {
      backButton.classList.remove('hidden');
    }

    if (mobileDisplay && data.result?.identifier) {
      mobileDisplay.textContent = data.result.identifier;
    }

    // Start resend timer if element exists
    this.startResendTimer();
  },

  /**
   * Handle login success
   * @param {object} data - Response data
   */
  handleLoginSuccess(data) {
    // Store auth token
    if (data.result?.auth) {
      localStorage.setItem('auth_token', data.result.auth);
      axios.defaults.headers.common['Authorization'] = `Bearer ${data.result.auth}`;
    }

    // Redirect after success message
    setTimeout(() => {
      window.location.href = '/dashboard';
    }, 1000);
  },

  /**
   * Start resend timer for OTP
   */
  startResendTimer() {
    const timerElement = document.getElementById('timer');
    const resendButton = document.getElementById('resend-button');

    if (!timerElement || !resendButton) return;

    let timeLeft = 60;
    resendButton.disabled = true;
    timerElement.textContent = timeLeft;

    const timer = setInterval(() => {
      timeLeft--;
      timerElement.textContent = timeLeft;

      if (timeLeft <= 0) {
        clearInterval(timer);
        resendButton.disabled = false;
        resendButton.textContent = 'ارسال مجدد';
      }
    }, 1000);
  },

  /**
   * Initialize OTP input handling
   */
  initOTPInputs() {
    const inputs = document.querySelectorAll('.otp-input');
    const otpForm = document.getElementById('otp-form');

    if (inputs.length === 0) return;

    // Set up OTP input event listeners
    inputs.forEach((input, index) => {
      input.addEventListener('input', (e) => {
        const value = e.target.value.replace(/\D/g, '');
        e.target.value = value;

        // Move to next input
        if (value && index < inputs.length - 1) {
          inputs[index + 1].focus();
        }

        // Check if OTP is complete
        this.checkOTPComplete();
      });

      input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !input.value && index > 0) {
          inputs[index - 1].focus();
        }
      });

      input.addEventListener('paste', (e) => {
        e.preventDefault();
        const paste = (e.clipboardData || window.clipboardData).getData('text');
        const digits = paste.replace(/\D/g, '').slice(0, 4);

        digits.split('').forEach((digit, i) => {
          if (inputs[i]) {
            inputs[i].value = digit;
            if (i < inputs.length - 1) inputs[i + 1].focus();
          }
        });

        this.checkOTPComplete();
      });
    });

    // Set up resend button
    const resendButton = document.getElementById('resend-button');
    if (resendButton) {
      resendButton.addEventListener('click', () => {
        const mobileForm = document.getElementById('mobile-form');
        if (mobileForm) {
          mobileForm.dispatchEvent(new Event('submit'));
        }
      });
    }
  },

  /**
   * Initialize back button for auth forms
   */
  initBackButton() {
    const backButton = document.getElementById('back-button');
    if (backButton) {
      backButton.addEventListener('click', () => {
        this.showMobileStep();
      });
    }
  },

  /**
   * Show mobile input step
   */
  showMobileStep() {
    const stepOtp = document.getElementById('step-otp');
    const stepMobile = document.getElementById('step-mobile');
    const backButtonContainer = document.getElementById('back-button-container');

    if (stepOtp && stepMobile) {
      stepOtp.classList.add('hidden');
      stepMobile.classList.remove('hidden');
    }

    if (backButtonContainer) {
      backButtonContainer.classList.add('hidden');
    }

    // Clear OTP inputs
    document.querySelectorAll('.otp-input').forEach(input => {
      input.value = '';
    });

    // Focus on mobile input
    const mobileInput = document.getElementById('mobile-input');
    if (mobileInput) {
      mobileInput.focus();
    }
  },

  /**
   * Validate a form
   * @param {HTMLFormElement} form - Form element to validate
   * @returns {boolean} True if form is valid
   */
  validateForm(form) {
    let isValid = true;
    const fields = form.querySelectorAll('[required], [data-validate]');

    fields.forEach(field => {
      field.classList.remove('border-red-500');

      // Check required fields
      if (field.hasAttribute('required') && !validation.validateRequired(field.value)) {
        isValid = false;
        field.classList.add('border-red-500');
        this.showFieldError(field, 'این فیلد اجباری است');
      }

      // Validate mobile numbers
      if (field.dataset.validate === 'mobile' && field.value && !validation.validateMobile(field.value)) {
        isValid = false;
        field.classList.add('border-red-500');
        this.showFieldError(field, 'شماره موبایل معتبر نیست');
      }

      // Validate email
      if (field.dataset.validate === 'email' && field.value && !validation.validateEmail(field.value)) {
        isValid = false;
        field.classList.add('border-red-500');
        this.showFieldError(field, 'ایمیل معتبر نیست');
      }

      // Validate OTP
      if (field.dataset.validate === 'otp' && field.value && !validation.validateOTP(field.value)) {
        isValid = false;
        field.classList.add('border-red-500');
        this.showFieldError(field, 'کد تایید باید 4 رقم باشد');
      }
    });

    return isValid;
  },

  /**
   * Show field error message
   * @param {HTMLElement} field - Field element
   * @param {string} message - Error message
   */
  showFieldError(field, message) {
    // Remove existing error message
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) existingError.remove();

    // Add error message
    const errorElement = document.createElement('span');
    errorElement.className = 'field-error text-red-500 text-sm mt-1 block';
    errorElement.textContent = message;
    field.parentNode.appendChild(errorElement);
  },

  /**
   * Initialize search functionality
   */
  initSearch() {
    document.querySelectorAll('[data-search]').forEach(input => {
      input.addEventListener('input', utilities.debounce((e) => {
        const searchTerm = e.target.value.toLowerCase();
        const targetSelector = input.dataset.search;
        const items = document.querySelectorAll(targetSelector);

        items.forEach(item => {
          const text = item.textContent.toLowerCase();
          item.style.display = text.includes(searchTerm) ? '' : 'none';
        });
      }, 300));
    });
  }

};
