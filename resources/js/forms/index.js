/**
 * Form Utilities
 * OTP input handling, back button, and search functionality
 * Auth actions are registered in bootstrap.js
 */

import { utilities } from '@/utilities.js';
import { useForm } from '@/composables/use-form.js';

let isFormsInitialized = false;

// ============================================
// Search Functionality
// ============================================

/**
 * Initialize search functionality
 */
export const initSearch = () => {
  document.querySelectorAll('[data-search]').forEach((input) => {
    input.addEventListener(
      'input',
      utilities.debounce((e) => {
        const searchTerm = e.target.value.toLowerCase().trim();
        const targetSelector = input.dataset.search;
        const items = document.querySelectorAll(targetSelector);

        items.forEach((item) => {
          const text = item.textContent.toLowerCase();
          const matches = searchTerm === '' || text.includes(searchTerm);
          item.style.display = matches ? '' : 'none';
        });
      }, 300),
    );
  });
};

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
  initSearch();
};

// ============================================
// Exports
// ============================================

export const forms = {
  initSearch,
  initForms,
};

export { useForm };
