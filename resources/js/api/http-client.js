/**
 * HTTP Client with Axios
 * Handles all HTTP requests with centralized interceptors
 * Accepts URLs generated via window.route() (Ziggy)
 * Configured for Laravel Sanctum with HttpOnly cookie authentication
 */

import axios from 'axios';
import {getCookie} from "@/utils/cookie.js";

/**
 * Get CSRF token from meta tag or cookie
 * @returns {string|null} CSRF token
 */
const getCsrfToken = () => {
  // Try to get from meta tag first
  const metaTag = document.querySelector('meta[name="csrf-token"]');
  if (metaTag) {
    return metaTag.getAttribute('content');
  }

  // Try to get from cookie
  const cookies = document.cookie.split(';');
  for (let cookie of cookies) {
    const [name, value] = cookie.trim().split('=');
    if (name === 'XSRF-TOKEN') {
      return decodeURIComponent(value);
    }
  }

  return null;
};

/**
 * Create and configure axios instance
 * Configured for Laravel 12 with Sanctum CSRF protection
 */
const createHttpClient = () => {
  const instance = axios.create({
    timeout: 10000,
    // Enable credentials for cross-domain requests (Sanctum)
    withCredentials: true,
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
    },
  });

  // Request interceptor
  instance.interceptors.request.use(
    (config) => {
      // Add CSRF token to all non-GET requests
      if (config.method !== 'get') {
        const csrfToken = getCsrfToken();
        if (csrfToken) {
          config.headers['X-CSRF-TOKEN'] = csrfToken;
        }
      }

      const token = getCookie('token');
      if (token) {
          config.headers.Authorization = `Bearer ${token}`;
      }

      // Token is automatically sent via HttpOnly cookie by the browser
      // No manual Authorization header needed for Sanctum
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

// Create singleton instance
export const httpClient = createHttpClient();
