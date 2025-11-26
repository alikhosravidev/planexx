/**
 * Auth - OTP Input Handling
 */

import { notifications } from '../notifications.js';

const CONFIG = {
  OTP_LENGTH: 4,
  AUTO_SUBMIT_DELAY: 300,
};

/**
 * Clear all OTP inputs
 */
export const clearOTPInputs = () => {
  document.querySelectorAll('.otp-input').forEach((input) => {
    input.value = '';
    input.classList.remove('border-danger', 'border-success');
  });
};

/**
 * Focus first OTP input
 */
const focusFirstOTPInput = () => {
  const firstInput = document.querySelector('.otp-input[data-index="0"]');
  if (firstInput) {
    setTimeout(() => firstInput.focus(), 100);
  }
};

/**
 * Get complete OTP value
 * @returns {string}
 */
const getOTPValue = () => {
  const inputs = document.querySelectorAll('.otp-input');
  return Array.from(inputs).map((input) => input.value).join('');
};

/**
 * Check if OTP is complete and handle submission
 */
const checkOTPComplete = () => {
  const otp = getOTPValue();

  if (otp.length === CONFIG.OTP_LENGTH) {
    // Validate all digits
    if (!/^\d+$/.test(otp)) {
      notifications.showError('کد تایید فقط باید شامل اعداد باشد');
      return;
    }

    // Set OTP in hidden password field
    const passwordInput = document.getElementById('otp-password');
    if (passwordInput) {
      passwordInput.value = otp;
    }

    // Auto-submit with delay
    const otpForm = document.getElementById('otp-form');
    if (otpForm) {
      setTimeout(() => {
        otpForm.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
      }, CONFIG.AUTO_SUBMIT_DELAY);
    }
  }
};

/**
 * Initialize OTP input handling
 */
export const initOTPInputs = () => {
  const inputs = document.querySelectorAll('.otp-input');
  if (inputs.length === 0) return;

  inputs.forEach((input, index) => {
    if (input.dataset.initialized === 'true') return;
    input.dataset.initialized = 'true';

    input.addEventListener('input', (e) => {
      const value = e.target.value.replace(/\D/g, '');
      e.target.value = value;

      if (value && index < inputs.length - 1) {
        inputs[index + 1].focus();
      }

      checkOTPComplete();
    });

    input.addEventListener('keydown', (e) => {
      if (e.key === 'Backspace' && !input.value && index > 0) {
        inputs[index - 1].focus();
      }
      if (e.key === 'ArrowLeft' && index > 0) {
        e.preventDefault();
        inputs[index - 1].focus();
      }
      if (e.key === 'ArrowRight' && index < inputs.length - 1) {
        e.preventDefault();
        inputs[index + 1].focus();
      }
    });

    input.addEventListener('paste', (e) => {
      e.preventDefault();
      const paste = (e.clipboardData || window.clipboardData).getData('text');
      const digits = paste.replace(/\D/g, '').slice(0, CONFIG.OTP_LENGTH);

      digits.split('').forEach((digit, i) => {
        if (inputs[i]) {
          inputs[i].value = digit;
        }
      });

      const lastIndex = Math.min(digits.length, inputs.length) - 1;
      if (lastIndex >= 0) {
        inputs[lastIndex].focus();
      }

      checkOTPComplete();
    });

    input.addEventListener('focus', () => input.select());
  });
};

// Auto-initialize
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initOTPInputs, { once: true });
} else {
  initOTPInputs();
}
