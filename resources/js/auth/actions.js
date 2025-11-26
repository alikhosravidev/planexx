/**
 * Authentication Custom AJAX Actions
 * Handles auth-specific response actions for the declarative AJAX system
 * Register these actions once on app init
 */

import { registerAction } from '../api/ajax-handler.js';
import { notifications } from '../notifications.js';
import { route } from 'ziggy-js';
import { cookieUtils } from '../api/http-client.js';
import { initOTPInputs } from './otp.js';

const CONFIG = {
  RESEND_TIMER_SECONDS: 60,
  OTP_LENGTH: 4,
  AUTO_SUBMIT_DELAY: 300,
  REDIRECT_DELAY: 1000,
  get DASHBOARD_URL() {
    return route('dashboard');
  },
};

let resendTimerInterval = null;

/**
 * Clear all OTP inputs
 */
const clearOTPInputs = () => {
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
 * Start resend timer for OTP
 */
const startResendTimer = () => {
  const timerElement = document.getElementById('timer');
  const resendButton = document.getElementById('resend-button');

  if (!timerElement || !resendButton) return;

  if (resendTimerInterval) {
    clearInterval(resendTimerInterval);
  }

  let timeLeft = CONFIG.RESEND_TIMER_SECONDS;
  resendButton.disabled = true;
  timerElement.textContent = timeLeft;

  resendTimerInterval = setInterval(() => {
    timeLeft--;
    timerElement.textContent = timeLeft;

    if (timeLeft <= 0) {
      clearInterval(resendTimerInterval);
      resendTimerInterval = null;
      resendButton.disabled = false;
    }
  }, 1000);
};

/**
 * Handle OTP step display
 */
registerAction('show-otp-step', (data, formEl) => {
  const stepMobile = document.getElementById('step-mobile');
  const stepOtp = document.getElementById('step-otp');
  const backButton = document.getElementById('back-button-container');
  const mobileDisplay = document.getElementById('mobile-display');
  const otpIdentifierInput = document.getElementById('otp-identifier');
  const resendButton = document.getElementById('resend-button');

  if (!stepMobile || !stepOtp) {
    console.error('Auth step elements not found');
    return;
  }

  // Toggle visibility
  stepMobile.classList.add('hidden');
  stepOtp.classList.remove('hidden');

  if (backButton) {
    backButton.classList.remove('hidden');
  }

  const identifier = data?.identifier;
  if (mobileDisplay && identifier) {
    mobileDisplay.textContent = identifier;
  }

  if (otpIdentifierInput && identifier) {
    otpIdentifierInput.value = identifier;
  }

  if (resendButton && identifier) {
    const baseUrl = resendButton.getAttribute('data-action');
    if (baseUrl) {
      try {
        const url = new URL(baseUrl, window.location.origin);
        url.searchParams.set('identifier', identifier);
        url.searchParams.set('authType', 'otp');
        resendButton.setAttribute('data-action', url.toString());
      } catch (e) {
        console.error('Error updating resend URL:', e);
      }
    }
  }

  clearOTPInputs();
  focusFirstOTPInput();
  // Ensure OTP input listeners are attached (idempotent)
  initOTPInputs();
  startResendTimer();
});

/**
 * Handle resend success
 */
registerAction('resend-success', (data) => {
  notifications.showSuccess(data?.message || 'کد جدید ارسال شد');
  clearOTPInputs();
  focusFirstOTPInput();
  startResendTimer();
});

/**
 * Handle login success
 * CRITICAL: Token MUST be saved before redirect
 */
registerAction('login-success', (data) => {
  const authData = data?.auth;
  const token = typeof authData === 'string' ? authData : authData?.token;

  // ✅ MUST save token before redirect
  if (token) {
    cookieUtils.set('token', token, 30);
    const redirectUrl = data?.redirect_url || CONFIG.DASHBOARD_URL;
    setTimeout(() => {
      window.location.href = redirectUrl;
    }, CONFIG.REDIRECT_DELAY);
  } else {
    console.error('❌ No token in login response:', data);
    // Do NOT redirect when token is missing (e.g., invalid OTP)
    return;
  }
});

/**
 * Handle logout - Backend handles token clearing, JS only redirects
 */
registerAction('logout', async (data, button) => {
  // Token is cleared by the backend - do NOT clear here
  // Just redirect to login page
  const redirectUrl = data?.result?.redirect_url || route('login');

  setTimeout(() => {
    window.location.href = redirectUrl;
  }, CONFIG.REDIRECT_DELAY);
});
