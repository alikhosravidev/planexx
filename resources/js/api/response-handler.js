/**
 * Response Handler
 * Centralized handling for API responses and errors
 */

import { notifications } from '@/notifications.js';

/**
 * Handle successful response
 * @param {object} response - Axios response object
 * @param {object} options - Handling options
 * @returns {any} Response data or result
 */
export const handleSuccess = (response, options = {}) => {
  const { showMessage = true, messageKey = 'message' } = options;

  const data = response.data;

  // Show success message if exists and enabled
  if (showMessage && data[messageKey]) {
    notifications.showSuccess(data[messageKey]);
  }

  // Return result data or full response data
  return data.result || data;
};

/**
 * Handle API error response
 * @param {Error} error - Axios error object
 * @param {object} options - Error handling options
 * @throws {Error} Re-throws error for caller to handle
 */
export const handleError = (error, options = {}) => {
  const { showMessage = true, customMessage } = options;

  const status = error.response?.status;
  const data = error.response?.data;

  // 401 - Unauthorized (handled by interceptor, but log it)
  if (status === 401) {
    if (showMessage) {
      notifications.showError(
        'جلسه شما منقضی شده است. لطفاً دوباره وارد شوید.',
      );
    }
    throw error;
  }

  // 403 - Forbidden
  if (status === 403) {
    if (showMessage) {
      notifications.showError('شما اجازه دسترسی به این منبع را ندارید.');
    }
    throw error;
  }

  // 422 - Validation errors
  if (status === 422) {
    if (showMessage) {
      if (data.message) {
        notifications.showError(data.message);
      }

      const validationErrors = data.errors || {};

      const errorMessages = validationErrors
        .map((item) => {
          if (item && typeof item === 'object' && 'message' in item) {
            return item.message;
          }
          return item;
        })
        .filter(Boolean);

      errorMessages.forEach((errorMessage) =>
        notifications.showError(errorMessage),
      );
    }

    // Return validation errors for form handling
    return {
      isValidationError: true,
      errors: data.errors || {},
      message: data.message || 'لطفاً فیلدهای نامعتبر را بررسی کنید.',
    };
  }

  // 500 - Server error
  if (status >= 500) {
    if (showMessage) {
      notifications.showError(
        customMessage || 'خطایی در سرور رخ داده است. لطفاً دوباره تلاش کنید.',
      );
    }
    throw error;
  }

  // Network error
  if (!error.response) {
    if (showMessage) {
      notifications.showError(
        customMessage ||
          'خطا در اتصال به سرور. لطفاً اتصال اینترنتی خود را بررسی کنید.',
      );
    }
    throw error;
  }

  // Generic error
  const errorMessage =
    data?.message || customMessage || 'خطای نامعلومی رخ داده است.';
  if (showMessage) {
    notifications.showError(errorMessage);
  }

  throw error;
};

/**
 * Extract validation errors from response
 * @param {Error} error - Axios error object
 * @returns {object} Validation errors object
 */
export const getValidationErrors = (error) => {
  if (error.response?.status === 422) {
    return error.response.data.errors || {};
  }
  return {};
};

/**
 * Check if response is successful
 * @param {object} response - Axios response object
 * @returns {boolean} True if successful
 */
export const isSuccessful = (response) => {
  return response.data.status === true || response.status === 200;
};
