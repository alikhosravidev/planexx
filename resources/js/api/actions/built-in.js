/**
 * Built-in AJAX Response Actions
 * Standard actions for common UI operations
 */

import { formService } from '@/services/form-service.js';
import { getAction } from './registry.js';

const CONFIG = {
    REDIRECT_DELAY: 500,
};

/**
 * Get target element from form/button
 * @param {HTMLElement} element - Form or button element
 * @returns {HTMLElement|null}
 */
const getTargetElement = (element) => {
  const targetSelector = element.getAttribute('data-target');
  if (!targetSelector) return null;
  return document.querySelector(targetSelector);
};

/**
 * Built-in response actions
 */
export const builtInActions = {
  /**
   * Reload current page
   */
  'reload': () => {
    return new Promise((resolve) => {
        setTimeout(() => {
            window.location.reload();
            resolve();
        }, CONFIG.REDIRECT_DELAY);
    });
  },

  /**
   * Redirect to URL from response.redirect_url
   */
  'redirect': (data) => {
    const url = data.redirect_url || data.redirectUrl;
    if (url) {
        return new Promise((resolve) => {
            setTimeout(() => {
                window.location.href = url;
                resolve();
            }, CONFIG.REDIRECT_DELAY);
        });
    } else {
        return new Promise((resolve) => {
            setTimeout(() => {
                window.location.reload();
                resolve();
            }, CONFIG.REDIRECT_DELAY);
        });
    }
  },

  /**
   * Replace element content
   * Requires: data-target selector
   */
  'replace': (data, element) => {
    const target = getTargetElement(element);
    if (target && data.html) {
      target.innerHTML = data.html;
      target.dispatchEvent(new CustomEvent('content-replaced', { detail: data }));
    }
  },

  /**
   * Append element content
   * Requires: data-target selector
   */
  'append': (data, element) => {
    const target = getTargetElement(element);
    if (target && data.html) {
      const tempDiv = document.createElement('div');
      tempDiv.innerHTML = data.html;
      const newElement = tempDiv.firstElementChild;
      if (newElement) {
        target.appendChild(newElement);
        target.dispatchEvent(new CustomEvent('content-appended', { detail: data }));
      }
    }
  },

  /**
   * Prepend element content
   * Requires: data-target selector
   */
  'prepend': (data, element) => {
    const target = getTargetElement(element);
    if (target && data.html) {
      const tempDiv = document.createElement('div');
      tempDiv.innerHTML = data.html;
      const newElement = tempDiv.firstElementChild;
      if (newElement) {
        target.prepend(newElement);
        target.dispatchEvent(new CustomEvent('content-prepended', { detail: data }));
      }
    }
  },

  /**
   * Remove element from DOM
   * Requires: data-target selector
   */
  'remove': (data, element) => {
    const target = getTargetElement(element);
    if (target) {
      target.remove();
    }
  },

  /**
   * Toggle element visibility
   * Requires: data-target selector
   */
  'toggle': (data, element) => {
    const target = getTargetElement(element);
    if (target) {
      target.classList.toggle('hidden');
    }
  },

  /**
   * Add CSS class to element
   * Requires: data-target selector
   * Requires: data-class (space-separated classes)
   */
  'add-class': (data, element) => {
    const target = getTargetElement(element);
    const classes = element.getAttribute('data-class');
    if (target && classes) {
      target.classList.add(...classes.split(' '));
    }
  },

  /**
   * Remove CSS class from element
   * Requires: data-target selector
   * Requires: data-class (space-separated classes)
   */
  'remove-class': (data, element) => {
    const target = getTargetElement(element);
    const classes = element.getAttribute('data-class');
    if (target && classes) {
      target.classList.remove(...classes.split(' '));
    }
  },

  /**
   * Reset form
   */
  'reset': (data, element) => {
    if (element.tagName === 'FORM') {
      formService.resetForm(element);
    }
  },

  /**
   * Close modal
   * Requires: data-modal (modal selector)
   */
  'close-modal': (data, element) => {
    const modalSelector = element.getAttribute('data-modal');
    if (!modalSelector) return;

    const modal = document.querySelector(modalSelector);
    if (modal) {
      modal.classList.add('hidden');
    }
  },

  /**
   * Show modal
   * Requires: data-modal (modal selector)
   */
  'show-modal': (data, element) => {
    const modalSelector = element.getAttribute('data-modal');
    if (!modalSelector) return;

    const modal = document.querySelector(modalSelector);
    if (modal) {
      modal.classList.remove('hidden');
    }
  },

  /**
   * Navigate back in history
   */
  'back': () => {
    window.history.back();
  },

  /**
   * Execute custom action
   * Requires: custom-action (action name)
   * Custom action must be registered via registerAction()
   */
  'custom': (data, element) => {
    const actionName = element.getAttribute('custom-action');
    if (!actionName) {
      console.warn('Custom action specified but custom-action attribute is missing');
      return;
    }

    const actionHandler = getAction(actionName);
    if (actionHandler) {
      actionHandler(data, element);
    } else {
      console.warn(`Custom action "${actionName}" not registered. Use registerAction('${actionName}', handler) to register it.`);
    }
  },
};
