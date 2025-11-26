/**
 * Auth - UI helpers (back button, step switch)
 */

import { clearOTPInputs } from './otp.js';

/**
 * Show mobile input step and reset OTP UI state
 */
export const showMobileStep = () => {
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
  clearOTPInputs();

  // Focus on mobile input
  const mobileInput = document.getElementById('mobile-input');
  if (mobileInput) {
    mobileInput.focus();
    mobileInput.select();
  }
};

/**
 * Initialize back button behavior for auth flow
 */
export const initBackButton = () => {
  const backButton = document.getElementById('back-button');
  if (!backButton) return;

  if (backButton.dataset.initialized === 'true') return;
  backButton.dataset.initialized = 'true';

  backButton.addEventListener('click', (e) => {
    e.preventDefault();
    showMobileStep();
  });
};

// Auto-initialize
document.addEventListener('DOMContentLoaded', initBackButton, { once: true });
