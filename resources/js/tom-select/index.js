import {
  tomSelectService,
  initTomSelect,
  destroyTomSelect,
  getTomSelectInstance,
} from './service.js';

/**
 * Set value for a Tom-Select instance (synchronous)
 * @param {HTMLElement} el - Select element
 * @param {string|number} value - Value to set
 * @param {boolean} silent - Whether to trigger change event (default: false)
 * @returns {boolean} Success status
 */
export function setTomSelectValue(el, value, silent = false) {
  return tomSelectService.setValue(el, value, silent);
}

/**
 * Set value for a Tom-Select instance with automatic wait for initialization
 * @param {HTMLElement} el - Select element
 * @param {string|number} value - Value to set
 * @param {boolean} silent - Whether to trigger change event (default: false)
 * @param {number} timeout - Maximum wait time in ms (default: 2000)
 * @returns {Promise<boolean>} Success status
 */
export async function setTomSelectValueAsync(
  el,
  value,
  silent = false,
  timeout = 2000,
) {
  return tomSelectService.setValueAsync(el, value, silent, timeout);
}

/**
 * Wait for Tom-Select instance to be initialized
 * @param {HTMLElement} el - Select element
 * @param {number} timeout - Maximum wait time in ms (default: 2000)
 * @returns {Promise<Object|null>} Tom-Select instance or null if timeout
 */
export async function waitForTomSelectInstance(el, timeout = 2000) {
  return tomSelectService.waitForInstance(el, timeout);
}

export {
  tomSelectService,
  initTomSelect,
  destroyTomSelect,
  getTomSelectInstance,
};

document.addEventListener('DOMContentLoaded', () => {
  initTomSelect();
});

document.addEventListener('modal:opened', (e) => {
  const modal = e.detail?.modal || e.target;
  if (modal) {
    initTomSelect(modal);
  }
});

document.addEventListener('content:loaded', (e) => {
  const container = e.detail?.container || document;
  initTomSelect(container);
});
