/**
 * Form Service
 * Handles form submission, validation, and processing
 */

import { post, get, put, patch, del, getValidationErrors } from '../api/request.js';
import { notifications } from '../notifications.js';
import { validation, validationRules } from '../validation.js';

class FormService {
  /**
   * Submit form data via AJAX
   * @param {HTMLFormElement} form - Form element
   * @param {object} options - Submission options
   * @returns {Promise<object>} Response data
   */
  async submitForm(form, options = {}) {
    const {
      method = 'POST',
      route: routeUrl = form.getAttribute('action'),
      showMessage = true,
      onSuccess = null,
      onError = null,
      onValidationError = null,
    } = options;

    if (!routeUrl) {
      console.error('Form action URL not found');
      notifications.showError('خطا: مسیر فرم مشخص نشده است.');
      return null;
    }

    try {
      // Get form data
      const formData = new FormData(form);
      const isFileUpload = form.enctype === 'multipart/form-data';
      
      let data;
      
      if (isFileUpload) {
        // For file uploads, use FormData directly
        data = formData;
      } else {
        // For regular forms, convert to object
        data = {};
        formData.forEach((value, key) => {
          if (key.endsWith('[]')) {
            const cleanKey = key.slice(0, -2);
            if (!Array.isArray(data[cleanKey])) {
              data[cleanKey] = [];
            }
            data[cleanKey].push(value);
          } else if (Object.prototype.hasOwnProperty.call(data, key)) {
            if (!Array.isArray(data[key])) {
              data[key] = [data[key]];
            }
            data[key].push(value);
          } else {
            data[key] = value;
          }
        });
      }

      // Build and execute request
      let request;
      const httpMethod = method.toUpperCase();

      switch (httpMethod) {
        case 'GET':
          request = get(routeUrl).withParams(data);
          break;
        case 'POST':
          request = post(routeUrl).withData(data);
          break;
        case 'PUT':
          request = put(routeUrl).withData(data);
          break;
        case 'PATCH':
          request = patch(routeUrl).withData(data);
          break;
        case 'DELETE':
          request = del(routeUrl);
          break;
        default:
          request = post(routeUrl).withData(data);
      }

      request.withMessage(showMessage);

      const result = await request.execute();

      // Handle validation errors
      if (result?.isValidationError) {
        this.displayValidationErrors(form, result.errors);

        if (onValidationError) {
          onValidationError(result);
        }

        if (showMessage) {
          notifications.showError(result.message);
        }

        return result;
      }

      // Handle success
      if (onSuccess) {
        onSuccess(result);
      }

      return result;
    } catch (error) {
      // Validation errors from request
      if (error.response?.status === 422) {
        const errors = getValidationErrors(error);
        this.displayValidationErrors(form, errors);

        if (onValidationError) {
          onValidationError({ isValidationError: true, errors });
        }

        return { isValidationError: true, errors };
      }

      // Other errors
      if (onError) {
        onError(error);
      }

      return null;
    }
  }

  /**
   * Display validation errors on form fields
   * @param {HTMLFormElement} form - Form element
   * @param {object} errors - Validation errors object
   */
  displayValidationErrors(form, errors) {
    // Clear previous errors
    this.clearValidationErrors(form);

    // Display new errors
    Object.entries(errors).forEach(([fieldName, fieldErrors]) => {
      const field = form.querySelector(`[name="${fieldName}"]`);

      if (field) {
        // Add error class
        field.classList.add('border-red-500');

        // Display error message
        const messages = Array.isArray(fieldErrors) ? fieldErrors : [fieldErrors];
        const errorMessage = messages[0];

        this.showFieldError(field, errorMessage);
      }
    });
  }

  /**
   * Clear all validation errors on form
   * @param {HTMLFormElement} form - Form element
   */
  clearValidationErrors(form) {
    // Remove error classes
    form.querySelectorAll('.border-red-500').forEach((field) => {
      field.classList.remove('border-red-500');
    });

    // Remove error messages
    form.querySelectorAll('.field-error').forEach((error) => {
      error.remove();
    });
  }

  /**
   * Show field error message
   * @param {HTMLElement} field - Form field
   * @param {string} message - Error message
   */
  showFieldError(field, message) {
    // Remove existing error
    const existing = field.parentNode.querySelector('.field-error');
    if (existing) existing.remove();

    // Create error element
    const errorElement = document.createElement('span');
    errorElement.className = 'field-error text-red-500 text-sm mt-1 block';
    errorElement.textContent = message;
    field.parentNode.appendChild(errorElement);
  }

  /**
   * Reset form fields and clear errors
   * @param {HTMLFormElement} form - Form element
   */
  resetForm(form) {
    form.reset();
    this.clearValidationErrors(form);
  }

  /**
   * Get form data as object
   * @param {HTMLFormElement} form - Form element
   * @returns {object} Form data
   */
  getFormData(form) {
    const formData = new FormData(form);
    const data = {};
    formData.forEach((value, key) => {
      if (key.endsWith('[]')) {
        const cleanKey = key.slice(0, -2);
        if (!Array.isArray(data[cleanKey])) {
          data[cleanKey] = [];
        }
        data[cleanKey].push(value);
      } else if (Object.prototype.hasOwnProperty.call(data, key)) {
        if (!Array.isArray(data[key])) {
          data[key] = [data[key]];
        }
        data[key].push(value);
      } else {
        data[key] = value;
      }
    });
    return data;
  }

  /**
   * Set form field values
   * @param {HTMLFormElement} form - Form element
   * @param {object} data - Field values to set
   */
  setFormData(form, data) {
    Object.entries(data).forEach(([name, value]) => {
      const field = form.querySelector(`[name="${name}"]`);
      if (field) {
        if (field.type === 'checkbox' || field.type === 'radio') {
          field.checked = value;
        } else {
          field.value = value;
        }
      }
    });
  }

  /**
   * Validate form using client-side validation
   * @param {HTMLFormElement} form - Form element
   * @param {object} validationRules - Custom validation rules
   * @returns {boolean} True if valid
   */
  validateForm(form, validationRules = {}) {
    let isValid = true;
    const fields = form.querySelectorAll('[required], [data-validate]');

    fields.forEach((field) => {
      // Clear previous error
      field.classList.remove('border-red-500');

      // Check required
      if (field.hasAttribute('required') && !field.value.trim()) {
        isValid = false;
        field.classList.add('border-red-500');
        this.showFieldError(field, 'این فیلد اجباری است');
        return;
      }

      // Check custom validation rules
      const validateType = field.dataset.validate;
      if (validateType && field.value) {
        let isFieldValid = true;
        let errorMessage = 'فیلد نامعتبر است';

        // Built-in validations using centralized validation module
        switch (validateType) {
          case 'email':
            isFieldValid = validation.validateEmail(field.value);
            errorMessage = 'ایمیل معتبر نیست';
            break;
          case 'phone':
          case 'mobile':
            isFieldValid = validation.validateMobile(field.value);
            errorMessage = 'شماره موبایل معتبر نیست (فرمت: 09XXXXXXXXX)';
            break;
          case 'otp':
            isFieldValid = validation.validateOTP(field.value);
            errorMessage = 'کد تایید باید ۴ رقم باشد';
            break;
          case 'digits':
            isFieldValid = validation.validateDigitsOnly(field.value);
            errorMessage = 'فقط اعداد مجاز است';
            break;
          case 'url':
            isFieldValid = /^https?:\/\/.+/.test(field.value);
            errorMessage = 'آدرس وب معتبر نیست';
            break;
          case 'number':
            isFieldValid = !isNaN(field.value) && field.value !== '';
            errorMessage = 'باید عدد وارد کنید';
            break;
          default:
            // Custom validation rule
            if (validationRules[validateType]) {
              isFieldValid = validationRules[validateType](field.value);
              errorMessage = validationRules[`${validateType}_message`] || errorMessage;
            }
        }

        if (!isFieldValid) {
          isValid = false;
          field.classList.add('border-red-500');
          this.showFieldError(field, errorMessage);
        }
      }
    });

    return isValid;
  }

  /**
   * Get field error messages
   * @param {HTMLFormElement} form - Form element
   * @returns {object} Field errors
   */
  getFieldErrors(form) {
    const errors = {};

    form.querySelectorAll('.field-error').forEach((errorEl) => {
      const field = errorEl.previousElementSibling;
      if (field) {
        const fieldName = field.getAttribute('name');
        if (fieldName) {
          errors[fieldName] = errorEl.textContent;
        }
      }
    });

    return errors;
  }
}

// Create singleton instance
export const formService = new FormService();
