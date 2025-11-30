/**
 * General utility functions for Planexx
 */
export const utilities = {

  /**
   * Debounce function for search and filter operations
   * @param {Function} func - Function to debounce
   * @param {number} wait - Wait time in milliseconds
   * @returns {Function} Debounced function
   */
  debounce(func, wait) {
    let timeout;
    return function(...args) {
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(this, args), wait);
    };
  },

  /**
   * Format number to Persian locale
   * @param {number} number - Number to format
   * @returns {string} Formatted number
   */
  numberFormat(number) {
    return new Intl.NumberFormat('fa-IR').format(number);
  },

  /**
   * Load data from JSON endpoint
   * @param {string} url - URL to fetch data from
   * @returns {Promise<Object|null>} Loaded data or null on error
   */
  async loadData(url) {
    try {
      const response = await fetch(url);
      if (!response.ok) throw new Error('Failed to load data');
      return await response.json();
    } catch (error) {
      console.error('Error loading data:', error);
      return null;
    }
  },

  /**
   * Show confirmation dialog
   * @param {string} message - Confirmation message
   * @returns {boolean} User's choice
   */
  confirm(message) {
    return window.confirm(message);
  }

};
