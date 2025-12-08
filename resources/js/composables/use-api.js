/**
 * useApi Composable
 * Provides reactive API state and request methods
 * Useful for managing API calls in JavaScript applications
 */

import { get, post, put, patch, del } from '../api/request.js';
import { notifications } from '../notifications.js';

/**
 * Create a reactive API state object
 * @param {object} options - Configuration options
 * @returns {object} API state and methods
 */
export const useApi = (options = {}) => {
  const { showNotifications = true, onError = null } = options;

  // Create state object
  const state = {
    isLoading: false,
    error: null,
    data: null,
  };

  /**
   * Execute GET request
   * @param {string} url - Request URL
   * @param {object} params - Query parameters
   * @returns {Promise<any>} Response data
   */
  const getRequest = async (url, params = null) => {
    state.isLoading = true;
    state.error = null;

    try {
      const request = get(url);
      if (params) {
        request.withParams(params);
      }

      const result = await request.execute();
      state.data = result;
      return result;
    } catch (error) {
      state.error = error;
      if (onError) {
        onError(error);
      }
      return null;
    } finally {
      state.isLoading = false;
    }
  };

  /**
   * Execute POST request
   * @param {string} url - Request URL
   * @param {object} data - Request data
   * @returns {Promise<any>} Response data
   */
  const postRequest = async (url, data = null) => {
    state.isLoading = true;
    state.error = null;

    try {
      const request = post(url);
      if (data) {
        request.withData(data);
      }

      const result = await request.execute();
      state.data = result;
      return result;
    } catch (error) {
      state.error = error;
      if (onError) {
        onError(error);
      }
      return null;
    } finally {
      state.isLoading = false;
    }
  };

  /**
   * Execute PUT request
   * @param {string} url - Request URL
   * @param {object} data - Request data
   * @returns {Promise<any>} Response data
   */
  const putRequest = async (url, data = null) => {
    state.isLoading = true;
    state.error = null;

    try {
      const request = put(url);
      if (data) {
        request.withData(data);
      }

      const result = await request.execute();
      state.data = result;
      return result;
    } catch (error) {
      state.error = error;
      if (onError) {
        onError(error);
      }
      return null;
    } finally {
      state.isLoading = false;
    }
  };

  /**
   * Execute PATCH request
   * @param {string} url - Request URL
   * @param {object} data - Request data
   * @returns {Promise<any>} Response data
   */
  const patchRequest = async (url, data = null) => {
    state.isLoading = true;
    state.error = null;

    try {
      const request = patch(url);
      if (data) {
        request.withData(data);
      }

      const result = await request.execute();
      state.data = result;
      return result;
    } catch (error) {
      state.error = error;
      if (onError) {
        onError(error);
      }
      return null;
    } finally {
      state.isLoading = false;
    }
  };

  /**
   * Execute DELETE request
   * @param {string} url - Request URL
   * @returns {Promise<any>} Response data
   */
  const deleteRequest = async (url) => {
    state.isLoading = true;
    state.error = null;

    try {
      const result = await del(url).execute();
      state.data = result;
      return result;
    } catch (error) {
      state.error = error;
      if (onError) {
        onError(error);
      }
      return null;
    } finally {
      state.isLoading = false;
    }
  };

  /**
   * Reset state
   */
  const reset = () => {
    state.isLoading = false;
    state.error = null;
    state.data = null;
  };

  /**
   * Get error message
   * @returns {string|null} Error message or null
   */
  const getErrorMessage = () => {
    if (!state.error) return null;

    if (state.error.response?.data?.message) {
      return state.error.response.data.message;
    }

    return 'خطای نامعلومی رخ داده است';
  };

  // Return public API
  return {
    // State
    state,
    get isLoading() {
      return state.isLoading;
    },
    get error() {
      return state.error;
    },
    get data() {
      return state.data;
    },

    // Methods
    get: getRequest,
    post: postRequest,
    put: putRequest,
    patch: patchRequest,
    delete: deleteRequest,
    reset,
    getErrorMessage,
  };
};
