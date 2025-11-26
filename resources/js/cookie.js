/**
 * Cookie Utilities
 * Centralized cookie management
 * Prefer cookieUtils from http-client.js instead
 */

/**
 * Get cookie value by name
 * @param {string} name - Cookie name
 * @returns {string|null} Cookie value or null
 * @deprecated Use cookieUtils from api/http-client.js instead
 */
export const getCookie = (name) => {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
  return null;
};

/**
 * Set cookie value
 * @param {string} name - Cookie name
 * @param {string} value - Cookie value
 * @param {number|Date} expire - Expiry date or timestamp
 * @param {string} path - Cookie path
 * @deprecated Use cookieUtils from api/http-client.js instead
 */
export const setCookie = (name, value, expire, path = '/') => {
  const expiryDate = typeof expire === 'number' ? new Date(expire) : new Date(expire);
  document.cookie = `${name}=${value};expires=${expiryDate.toUTCString()};path=${path}`;
};

/**
 * Delete cookie by name
 * @param {string} name - Cookie name
 * @deprecated Use cookieUtils from api/http-client.js instead
 */
export const deleteCookie = (name) => {
  document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
};
