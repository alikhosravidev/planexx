/**
 * Request Builder
 * Helps build and execute API requests with proper configuration
 */

import { httpClient } from './http-client.js';
import { handleSuccess, handleError, getValidationErrors } from './response-handler.js';

/**
 * Base Request class for building API requests
 */
class Request {
  constructor(method, url) {
    this.method = method.toUpperCase();
    this.url = url;
    this.data = null;
    this.params = null;
    this.options = {
      showMessage: true,
      messageKey: 'message',
    };
  }

  /**
   * Set request data (for POST, PUT, PATCH)
   * @param {object|FormData} data - Request data
   * @returns {Request} This for chaining
   */
  withData(data) {
    this.data = data;
    return this;
  }

  /**
   * Set request parameters (for GET)
   * @param {object} params - Query parameters
   * @returns {Request} This for chaining
   */
  withParams(params) {
    this.params = params;
    return this;
  }

  /**
   * Show/hide success message
   * @param {boolean} show - Show message
   * @returns {Request} This for chaining
   */
  withMessage(show = true) {
    this.options.showMessage = show;
    return this;
  }

  /**
   * Set custom message key
   * @param {string} key - Message key in response
   * @returns {Request} This for chaining
   */
  withMessageKey(key) {
    this.options.messageKey = key;
    return this;
  }

  /**
   * Execute the request
   * @returns {Promise<any>} Response data or result
   * @throws {Error} If request fails
   */
  async execute() {
    try {
      const config = {
        method: this.method,
        url: this.url,
      };

      if (this.data) {
        config.data = this.data;
      }

      if (this.params) {
        config.params = this.params;
      }

      const response = await httpClient(config);
      return handleSuccess(response, this.options);
    } catch (error) {
      // Let the centralized handler process all error cases (including 422)
      return handleError(error, this.options);
    }
  }
}

/**
 * Helper function to create GET request
 * @param {string} url - Request URL
 * @returns {Request} Request instance
 */
export const get = (url) => new Request('GET', url);

/**
 * Helper function to create POST request
 * @param {string} url - Request URL
 * @returns {Request} Request instance
 */
export const post = (url) => new Request('POST', url);

/**
 * Helper function to create PUT request
 * @param {string} url - Request URL
 * @returns {Request} Request instance
 */
export const put = (url) => new Request('PUT', url);

/**
 * Helper function to create PATCH request
 * @param {string} url - Request URL
 * @returns {Request} Request instance
 */
export const patch = (url) => new Request('PATCH', url);

/**
 * Helper function to create DELETE request
 * @param {string} url - Request URL
 * @returns {Request} Request instance
 */
export const del = (url) => new Request('DELETE', url);

/**
 * Quick GET request
 * @param {string} url - Request URL
 * @param {object} params - Query parameters
 * @returns {Promise<any>} Response data
 */
export const quickGet = (url, params = null) => {
  const request = get(url);
  if (params) {
    request.withParams(params);
  }
  return request.execute();
};

/**
 * Quick POST request
 * @param {string} url - Request URL
 * @param {object} data - Request data
 * @returns {Promise<any>} Response data
 */
export const quickPost = (url, data = null) => {
  const request = post(url);
  if (data) {
    request.withData(data);
  }
  return request.execute();
};

/**
 * Quick DELETE request
 * @param {string} url - Request URL
 * @returns {Promise<any>} Response data
 */
export const quickDelete = (url) => {
  return del(url).execute();
};

export { Request, getValidationErrors };
