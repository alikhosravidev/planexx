/**
 * useForm Composable
 * Provides reactive form state and methods
 * Useful for managing form state in JavaScript applications
 */

import { formService } from '../services/form-service.js';

/**
 * Create a reactive form state object
 * @param {HTMLFormElement|string} formElement - Form element or selector
 * @param {object} options - Configuration options
 * @returns {object} Form state and methods
 */
export const useForm = (formElement, options = {}) => {
  const { autoValidate = true, clearErrorsOnInput = true } = options;

  // Get form element
  let form = formElement;
  if (typeof formElement === 'string') {
    form = document.querySelector(formElement);
  }

  if (!form) {
    console.error('Form element not found');
    return null;
  }

  // Create state object
  const state = {
    isSubmitting: false,
    isValid: true,
    errors: {},
    data: {},
  };

  /**
   * Update form data state
   */
  const updateData = () => {
    state.data = formService.getFormData(form);
  };

  /**
   * Clear field error on input
   */
  const handleFieldInput = (event) => {
    if (clearErrorsOnInput) {
      const field = event.target;
      field.classList.remove('border-red-500');

      const errorEl = field.parentNode.querySelector('.field-error');
      if (errorEl) {
        errorEl.remove();
      }

      delete state.errors[field.name];
    }

    updateData();
  };

  /**
   * Validate form
   * @returns {boolean} True if valid
   */
  const validate = (customRules = {}) => {
    state.isValid = formService.validateForm(form, customRules);
    state.errors = formService.getFieldErrors(form);
    return state.isValid;
  };

  /**
   * Submit form
   * @param {object} options - Submission options
   * @returns {Promise<object>} Response data
   */
  const submit = async (options = {}) => {
    // Validate first
    if (autoValidate && !validate(options.validationRules)) {
      return null;
    }

    state.isSubmitting = true;

    try {
      const result = await formService.submitForm(form, {
        method: options.method || form.getAttribute('data-method') || 'POST',
        route: options.route || form.getAttribute('action'),
        showMessage: options.showMessage !== false,
        onSuccess: options.onSuccess,
        onError: options.onError,
        onValidationError: (result) => {
          state.errors = result.errors || {};
          state.isValid = false;
          if (options.onValidationError) {
            options.onValidationError(result);
          }
        },
      });

      return result;
    } finally {
      state.isSubmitting = false;
    }
  };

  /**
   * Reset form
   */
  const reset = () => {
    formService.resetForm(form);
    state.isValid = true;
    state.errors = {};
    state.data = {};
  };

  /**
   * Get field value
   * @param {string} fieldName - Field name
   * @returns {any} Field value
   */
  const getFieldValue = (fieldName) => {
    const field = form.querySelector(`[name="${fieldName}"]`);
    if (field) {
      return field.type === 'checkbox' ? field.checked : field.value;
    }
    return null;
  };

  /**
   * Set field value
   * @param {string} fieldName - Field name
   * @param {any} value - Value to set
   */
  const setFieldValue = (fieldName, value) => {
    const field = form.querySelector(`[name="${fieldName}"]`);
    if (field) {
      if (field.type === 'checkbox' || field.type === 'radio') {
        field.checked = value;
      } else {
        field.value = value;
      }
      updateData();
    }
  };

  /**
   * Get field error
   * @param {string} fieldName - Field name
   * @returns {string|null} Error message or null
   */
  const getFieldError = (fieldName) => {
    return state.errors[fieldName] || null;
  };

  /**
   * Check if field has error
   * @param {string} fieldName - Field name
   * @returns {boolean} True if has error
   */
  const hasFieldError = (fieldName) => {
    return !!state.errors[fieldName];
  };

  // Set up event listeners
  form.querySelectorAll('input, textarea, select').forEach((field) => {
    field.addEventListener('input', handleFieldInput);
  });

  // Return public API
  return {
    // State
    form,
    state,
    get isSubmitting() {
      return state.isSubmitting;
    },
    get isValid() {
      return state.isValid;
    },
    get errors() {
      return state.errors;
    },
    get data() {
      return state.data;
    },

    // Methods
    validate,
    submit,
    reset,
    getFieldValue,
    setFieldValue,
    getFieldError,
    hasFieldError,
    updateData,
  };
};
