/**
 * HTTP Client with Axios
 * Handles all HTTP requests with centralized interceptors
 * Supports Ziggy route integration
 */

import axios from 'axios';

/**
 * Create and configure axios instance
 */
const createHttpClient = () => {
  const instance = axios.create({
    timeout: 10000,
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
    },
  });

  // Request interceptor - Add auth token if available
  instance.interceptors.request.use(
    (config) => {
      // Get token from cookie if available
      const token = getCookieValue('token');
      if (token) {
        config.headers.Authorization = `Bearer ${token}`;
      }
      return config;
    },
    (error) => {
      return Promise.reject(error);
    }
  );

  // Response interceptor - Handle responses globally
  instance.interceptors.response.use(
    (response) => {
      return response;
    },
    (error) => {
      // Handle 401 - Token expired or invalid
      if (error.response?.status === 401) {
      }

      // Handle 403 - Forbidden
      if (error.response?.status === 403) {
        // Let caller handle this
      }

      // Handle 422 - Validation errors
      if (error.response?.status === 422) {
        // Validation errors are returned as-is for form handling
      }

      return Promise.reject(error);
    }
  );

  return instance;
};

/**
 * Get cookie value by name
 * @param {string} name - Cookie name
 * @returns {string|null} Cookie value or null
 */
const getCookieValue = (name) => {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
  return null;
};

/**
 * Delete cookie by name
 * @param {string} name - Cookie name
 */
const deleteCookie = (name) => {
  document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
};

/**
 * Set cookie value
 * @param {string} name - Cookie name
 * @param {string} value - Cookie value
 * @param {number} days - Days until expiry (default: 30)
 */
const setCookieValue = (name, value, days = 30) => {
  const expires = new Date();
  expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
  document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
};

// Create singleton instance
export const httpClient = createHttpClient();

// Export utility functions
export const cookieUtils = {
  get: getCookieValue,
  set: setCookieValue,
  delete: deleteCookie,
};

// Make available globally for debugging
window.__httpClient = httpClient;
