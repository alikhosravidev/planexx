/**
 * Button Handler
 * Handles AJAX button/link clicks
 */

import { resolve } from '../../utils/di-container.js';
import { executeAction } from '../actions/executor.js';
import { getAction } from '../actions/registry.js';

// Get dependencies from DI container (can be mocked in tests)
const getHttpClient = () => resolve('httpClient');
const getNotifications = () => resolve('notifications');

/**
 * Parse button configuration
 * @param {HTMLElement} button
 * @returns {object}
 */
export const getButtonConfig = (button) => {
  const action =
    button.getAttribute('data-action') || button.getAttribute('href');
  const method = button.getAttribute('data-method') || 'POST';
  const actionsStr = button.getAttribute('data-on-success') || '';
  const loadingClass = button.getAttribute('data-loading-class') || '';
  const showMessage = button.getAttribute('data-show-message') !== 'false';
  const afterSuccessAction = button.getAttribute('custom-action');
  const confirmMessage = button.getAttribute('data-confirm');

  const actions = actionsStr
    .split(/[,\s]+/)
    .map((a) => a.trim())
    .filter((a) => a);

  return {
    action,
    method,
    actions,
    loadingClass,
    showMessage,
    afterSuccessAction,
    confirmMessage,
  };
};

/**
 * Extract data from button's data-param-* attributes
 * @param {HTMLElement} button
 * @returns {object}
 */
const extractDataFromButton = (button) => {
  const data = {};

  // Get all data-param-* attributes
  for (const [key, value] of Object.entries(button.dataset)) {
    if (key.startsWith('param')) {
      // data-param-user-id → userId
      const paramName = key
        .replace('param', '')
        .replace(/^./, (c) => c.toLowerCase());
      data[paramName] = value;
    }
  }

  return data;
};

/**
 * Add classes to element
 * @param {HTMLElement} element
 * @param {string} classString
 */
const addClasses = (element, classString) => {
  if (!element || !classString) return;
  const classes = classString.split(/\s+/).filter((c) => c.trim());
  if (classes.length > 0) {
    element.classList.add(...classes);
  }
};

/**
 * Remove classes from element
 * @param {HTMLElement} element
 * @param {string} classString
 */
const removeClasses = (element, classString) => {
  if (!element || !classString) return;
  const classes = classString.split(/\s+/).filter((c) => c.trim());
  if (classes.length > 0) {
    element.classList.remove(...classes);
  }
};

/**
 * Handle button AJAX click
 * @param {Event} event - Click event
 */
export const handleButtonClick = async (event) => {
  const button = event.currentTarget;

  if (!button || !button.hasAttribute('data-ajax')) {
    return;
  }

  if (event.preventDefault) {
    event.preventDefault();
  }

  // Get button configuration
  const config = getButtonConfig(button);

  if (!config.action) {
    console.error(
      'Button action URL not found. Add data-action or action attribute.',
    );
    return;
  }

  if (config.confirmMessage && !window.confirm(config.confirmMessage)) {
    return;
  }

  // Prevent multiple concurrent AJAX requests globally
  if (window.__ajaxRequestInProgress) {
    return;
  }
  window.__ajaxRequestInProgress = true;

  // Add loading state
  if (config.loadingClass) {
    addClasses(button, config.loadingClass);
  }

  // Add spinner class for visual feedback
  button.classList.add('spinner-left');

  // Disable button during request
  const originalDisabled = button.disabled;
  button.disabled = true;

  try {
    // Prepare request data
    const requestData = extractDataFromButton(button);

    // Get dependencies
    const httpClient = getHttpClient();
    const notifications = getNotifications();

    // Make request using httpClient
    const response = await httpClient.request({
      method: config.method,
      url: config.action,
      data: config.method !== 'GET' ? requestData : undefined,
      params: config.method === 'GET' ? requestData : undefined,
    });

    const result = response.data;

    // Show success message
    if (config.showMessage && result.message) {
      notifications.showSuccess(result.message);
    }

    // Dispatch success event
    button.dispatchEvent(
      new CustomEvent('ajax:success', {
        detail: { response: result, button },
        bubbles: true,
      }),
    );

    // Execute response actions (await to keep global lock until done)
    if (config.actions.length > 0) {
      for (const actionName of config.actions) {
        if (actionName === 'custom' && config.afterSuccessAction) {
          const actionHandler = getAction(config.afterSuccessAction);
          if (actionHandler) {
            const maybePromise = actionHandler(result, button);
            if (maybePromise instanceof Promise) {
              await maybePromise;
            }
          }
        } else {
          await executeAction(actionName, result.result, button);
        }
      }
    }
  } catch (error) {
    console.error('Button click error:', error);

    // Show error message
    if (config.showMessage) {
      const notifications = getNotifications();
      const errorMessage =
        error.response?.data?.message || error.message || 'خطایی رخ داده است';
      notifications.showError(errorMessage);
    }

    button.dispatchEvent(
      new CustomEvent('ajax:error', {
        detail: { error, button },
        bubbles: true,
      }),
    );
  } finally {
    if (config.loadingClass) {
      removeClasses(button, config.loadingClass);
    }
    button.classList.remove('spinner-left');
    // Restore button state
    button.disabled = originalDisabled;

    window.__ajaxRequestInProgress = false;
  }
};
