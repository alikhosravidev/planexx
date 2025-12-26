/**
 * Form Utilities
 * OTP input handling, back button, and search functionality
 * Auth actions are registered in bootstrap.js
 */

import { useForm } from './use-form.js';

let isFormsInitialized = false;

// ============================================
// Auto Initialize
// ============================================

/**
 * Initialize all form utilities
 */
export const initForms = () => {
  if (isFormsInitialized) {
    console.warn('Forms already initialized, skipping...');
    return;
  }
  isFormsInitialized = true;
};

// ============================================
// Exports
// ============================================

export const forms = {
  initForms,
};

export { useForm };
