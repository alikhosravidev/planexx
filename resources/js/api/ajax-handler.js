/**
 * AJAX Handler - Declarative AJAX System
 * Handles all AJAX requests based on HTML attributes
 * Zero manual JavaScript needed for form submissions!
 */

import { formService } from '@/services/form-service.js';
import { notifications } from '@/notifications.js';
import { cookieUtils } from './http-client.js';

let isInitialized = false;

/**
 * Registry for custom response actions
 * Register custom actions: registerAction('customName', (data) => { ... })
 */
const actionRegistry = new Map();

/**
 * Register a custom action
 * @param {string} name - Action name
 * @param {Function} handler - Action handler function
 */
export const registerAction = (name, handler) => {
  actionRegistry.set(name, handler);
};

/**
 * Get registered action
 * @param {string} name - Action name
 * @returns {Function|null} Action handler or null
 */
export const getAction = (name) => {
  return actionRegistry.get(name) || null;
};

/**
 * Built-in response actions
 */
const builtInActions = {
  /**
   * Reload current page
   */
  'reload': () => {
    window.location.reload();
  },

  /**
   * Redirect to URL from response.redirect_url
   */
  'redirect': (data) => {
    if (data.redirect_url || data.redirectUrl) {
      window.location.href = data.redirect_url || data.redirectUrl;
    }
  },

  /**
   * Replace element content
   * Requires: data-target selector
   */
  'replace': (data, formEl) => {
    const targetSelector = formEl.getAttribute('data-target');
    if (!targetSelector) return;

    const targetElement = document.querySelector(targetSelector);
    if (targetElement && data.html) {
      targetElement.innerHTML = data.html;
      // Dispatch event for other handlers
      targetElement.dispatchEvent(new CustomEvent('content-replaced', { detail: data }));
    }
  },

  /**
   * Append element content
   * Requires: data-target selector
   */
  'append': (data, formEl) => {
    const targetSelector = formEl.getAttribute('data-target');
    if (!targetSelector) return;

    const targetElement = document.querySelector(targetSelector);
    if (targetElement && data.html) {
      const tempDiv = document.createElement('div');
      tempDiv.innerHTML = data.html;
      targetElement.appendChild(tempDiv.firstElementChild);
      // Dispatch event for other handlers
      targetElement.dispatchEvent(new CustomEvent('content-appended', { detail: data }));
    }
  },

  /**
   * Prepend element content
   * Requires: data-target selector
   */
  'prepend': (data, formEl) => {
    const targetSelector = formEl.getAttribute('data-target');
    if (!targetSelector) return;

    const targetElement = document.querySelector(targetSelector);
    if (targetElement && data.html) {
      const tempDiv = document.createElement('div');
      tempDiv.innerHTML = data.html;
      targetElement.prepend(tempDiv.firstElementChild);
      // Dispatch event for other handlers
      targetElement.dispatchEvent(new CustomEvent('content-prepended', { detail: data }));
    }
  },

  /**
   * Remove element from DOM
   * Requires: data-target selector
   */
  'remove': (data, formEl) => {
    const targetSelector = formEl.getAttribute('data-target');
    if (!targetSelector) return;

    const targetElement = document.querySelector(targetSelector);
    if (targetElement) {
      targetElement.remove();
    }
  },

  /**
   * Toggle element visibility
   * Requires: data-target selector
   */
  'toggle': (data, formEl) => {
    const targetSelector = formEl.getAttribute('data-target');
    if (!targetSelector) return;

    const targetElement = document.querySelector(targetSelector);
    if (targetElement) {
      targetElement.classList.toggle('hidden');
    }
  },

  /**
   * Add CSS class to element
   * Requires: data-target selector
   * Requires: data-class (space-separated classes)
   */
  'add-class': (data, formEl) => {
    const targetSelector = formEl.getAttribute('data-target');
    const classes = formEl.getAttribute('data-class');
    if (!targetSelector || !classes) return;

    const targetElement = document.querySelector(targetSelector);
    if (targetElement) {
      targetElement.classList.add(...classes.split(' '));
    }
  },

  /**
   * Remove CSS class from element
   * Requires: data-target selector
   * Requires: data-class (space-separated classes)
   */
  'remove-class': (data, formEl) => {
    const targetSelector = formEl.getAttribute('data-target');
    const classes = formEl.getAttribute('data-class');
    if (!targetSelector || !classes) return;

    const targetElement = document.querySelector(targetSelector);
    if (targetElement) {
      targetElement.classList.remove(...classes.split(' '));
    }
  },

  /**
   * Reset form
   */
  'reset': (data, formEl) => {
    formService.resetForm(formEl);
  },

  /**
   * Close modal
   * Requires: data-modal (modal selector)
   */
  'close-modal': (data, formEl) => {
    const modalSelector = formEl.getAttribute('data-modal');
    if (!modalSelector) return;

    const modal = document.querySelector(modalSelector);
    if (modal) {
      modal.classList.add('hidden');
      // Or using Bootstrap/other modal libs
      // const bsModal = bootstrap.Modal.getInstance(modal);
      // bsModal?.hide();
    }
  },

  /**
   * Show modal
   * Requires: data-modal (modal selector)
   */
  'show-modal': (data, formEl) => {
    const modalSelector = formEl.getAttribute('data-modal');
    if (!modalSelector) return;

    const modal = document.querySelector(modalSelector);
    if (modal) {
      modal.classList.remove('hidden');
      // Or using Bootstrap/other modal libs
      // const bsModal = new bootstrap.Modal(modal);
      // bsModal.show();
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
   * Requires: data-after-success (action name)
   * Custom action must be registered via registerAction()
   */
  'custom': (data, formEl) => {
    const actionName = formEl.getAttribute('data-after-success');
    if (!actionName) return;

    const actionHandler = getAction(actionName);
    if (actionHandler) {
      actionHandler(data, formEl);
    } else {
      console.warn(`Custom action "${actionName}" not registered`);
    }
  },
};

/**
 * Execute response action
 * @param {string} actionName - Action name
 * @param {object} data - Response data
 * @param {HTMLFormElement} formEl - Form element
 */
export const executeAction = (actionName, data, formEl) => {
  const actionHandler = builtInActions[actionName] || getAction(actionName);

  if (actionHandler) {
    actionHandler(data, formEl);
  } else {
    console.warn(`Unknown action: "${actionName}"`);
  }
};

/**
 * Handle form submission
 * @param {Event} event - Submit event
 */
export const handleFormSubmit = async (event) => {
  const form = event.target;

  // Don't prevent default for non-AJAX forms
  if (!form.hasAttribute('data-ajax')) {
    return;
  }

  event.preventDefault();

  // Get form configuration from attributes
  const ajaxConfig = getAjaxConfig(form);

  // Validate if needed
  if (ajaxConfig.validate !== false) {
    if (!formService.validateForm(form)) {
      if (ajaxConfig.validationError) {
        notifications.showError(ajaxConfig.validationError);
      } else {
        notifications.showError('لطفاً تمام فیلدها را به درستی پر کنید');
      }
      return;
    }
  }

  // Show loading state if specified
  if (ajaxConfig.loadingClass) {
      addClasses(form, ajaxConfig.loadingClass);
  }

  try {
    // Submit form
    const result = await formService.submitForm(form, {
      method: ajaxConfig.method,
      route: ajaxConfig.action,
      showMessage: ajaxConfig.showMessage !== false,
      onValidationError: (validationResult) => {
        formService.displayValidationErrors(form, validationResult.errors);
      },
    });

    // Handle validation errors
    if (result?.isValidationError) {
      // Already handled by displayValidationErrors
      return;
    }

    // Dispatch custom event
    form.dispatchEvent(
      new CustomEvent('ajax:success', {
        detail: { response: result, form },
      })
    );

    // Execute response actions
    if (ajaxConfig.actions.length > 0) {
      for (const actionName of ajaxConfig.actions) {
        executeAction(actionName, result, form);
      }
    } else if (ajaxConfig.defaultAction) {
      executeAction(ajaxConfig.defaultAction, result, form);
    }
  } catch (error) {
    console.error('Form submission error:', error);

    // Dispatch error event
    form.dispatchEvent(
      new CustomEvent('ajax:error', {
        detail: { error, form },
      })
    );

    // Execute error actions if specified
    if (ajaxConfig.errorActions.length > 0) {
      for (const actionName of ajaxConfig.errorActions) {
        executeAction(actionName, {}, form);
      }
    }
  } finally {
    // Remove loading state
    if (ajaxConfig.loadingClass) {
        removeClasses(form, ajaxConfig.loadingClass);
    }
  }
};

/**
 * Parse AJAX configuration from form attributes
 * @param {HTMLFormElement} form - Form element
 * @returns {object} Configuration object
 */
export const getAjaxConfig = (form) => {
  // Get action URL (from form action attribute)
  // Note: data-action is for custom response action, not for form URL
  const action = form.getAttribute('action');

  // Get HTTP method
  const method = form.getAttribute('data-method') || form.getAttribute('method') || 'POST';

  // Parse response actions (comma or space separated)
  const actionsStr = form.getAttribute('data-on-success') || '';
  const actions = actionsStr
    .split(/[,\s]+/)
    .map((a) => a.trim())
    .filter((a) => a);

  // Get custom action name (for data-on-success="custom")
  const afterSuccessAction = form.getAttribute('data-after-success');

  // Parse error actions
  const errorActionsStr = form.getAttribute('data-on-error') || '';
  const errorActions = errorActionsStr
    .split(/[,\s]+/)
    .map((a) => a.trim())
    .filter((a) => a);

  // Get other options
  const validate = form.getAttribute('data-validate') !== 'false';
  const showMessage = form.getAttribute('data-show-message') !== 'false';
  const loadingClass = form.getAttribute('data-loading-class') || '';
  const defaultAction = form.getAttribute('data-default-action') || '';
  const validationError = form.getAttribute('data-validation-error') || '';

  return {
    action,
    method,
    actions,
    errorActions,
    validate,
    showMessage,
    loadingClass,
    defaultAction,
    validationError,
    afterSuccessAction,
  };
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
    const config = {
        action: button.getAttribute('data-action') || button.getAttribute('href'),
        method: button.getAttribute('data-method') || 'POST',
        actionsStr: button.getAttribute('data-on-success') || '',
        loadingClass: button.getAttribute('data-loading-class') || '',
        showMessage: button.getAttribute('data-show-message') !== 'false',
        afterSuccessAction: button.getAttribute('data-after-success'),
        // ✅ اضافه کردن امکان ارسال داده از data-* attributes
        data: button.dataset,
    };

    config.actions = config.actionsStr
        .split(/[,\s]+/)
        .map((a) => a.trim())
        .filter((a) => a);

    if (!config.action) {
        console.error('Button action URL not found. Add data-action attribute.');
        return;
    }

    // Add loading state
    if (config.loadingClass) {
        addClasses(button, config.loadingClass);
    }

    // ✅ Disable button during request
    const originalDisabled = button.disabled;
    button.disabled = true;

    try {
        // Get auth token from cookie
        const token = cookieUtils.get('token');

        const response = await fetch(config.action, {
            method: config.method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                ...(document.querySelector('meta[name="csrf-token"]') && {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }),
                ...(token && {
                    'Authorization': `Bearer ${token}`
                })
            },
            ...(config.method !== 'GET' && {
                body: JSON.stringify(extractDataFromButton(button))
            })
        });

        // Handle 401 Unauthorized
        if (response.status === 401) {
            return;
        }

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        // Show success message
        if (config.showMessage && result.message) {
            notifications.showSuccess(result.message);
        }

        // Dispatch success event
        button.dispatchEvent(
            new CustomEvent('ajax:success', {
                detail: { response: result, button },
                bubbles: true
            })
        );

        // Execute response actions
        if (config.actions.length > 0) {
            for (const actionName of config.actions) {
                if (actionName === 'custom' && config.afterSuccessAction) {
                    const actionHandler = getAction(config.afterSuccessAction);
                    if (actionHandler) {
                        actionHandler(result, button);
                    }
                } else {
                    executeAction(actionName, result, button);
                }
            }
        }

    } catch (error) {
        console.error('Button click error:', error);

        // Show error message
        if (config.showMessage) {
            notifications.showError(error.message || 'خطایی رخ داده است');
        }

        button.dispatchEvent(
            new CustomEvent('ajax:error', {
                detail: { error, button },
                bubbles: true
            })
        );

        // Execute error actions if custom action is set
        if (config.actions.length > 0) {
            for (const actionName of config.actions) {
                if (actionName === 'custom' && config.afterSuccessAction) {
                    const actionHandler = getAction(config.afterSuccessAction);
                    if (actionHandler) {
                        actionHandler({}, button);
                    }
                }
            }
        }
    } finally {
        if (config.loadingClass) {
            removeClasses(button, config.loadingClass);
        }
        // ✅ Restore button state
        button.disabled = originalDisabled;
    }
};


/**
 * Extract data from button's data-* attributes
 * @param {HTMLElement} button
 * @returns {Object}
 */
const extractDataFromButton = (button) => {
    const data = {};

    // Get all data-param-* attributes
    for (const [key, value] of Object.entries(button.dataset)) {
        if (key.startsWith('param')) {
            // data-param-user-id → userId
            const paramName = key.replace('param', '').replace(/^./, c => c.toLowerCase());
            data[paramName] = value;
        }
    }

    return data;
};

/**
 * Safely add classes to element (handles space-separated classes)
 * @param {HTMLElement} element
 * @param {string} classString
 */
const addClasses = (element, classString) => {
    if (!element || !classString) return;
    const classes = classString.split(/\s+/).filter(c => c.trim());
    if (classes.length > 0) {
        element.classList.add(...classes);
    }
};

/**
 * Safely remove classes from element (handles space-separated classes)
 * @param {HTMLElement} element
 * @param {string} classString
 */
const removeClasses = (element, classString) => {
    if (!element || !classString) return;
    const classes = classString.split(/\s+/).filter(c => c.trim());
    if (classes.length > 0) {
        element.classList.remove(...classes);
    }
};

/**
 * Initialize AJAX handler for all forms and buttons
 * Call this once on page load
 */
export const initializeAjaxHandler = () => {
    if (isInitialized) {
        console.warn('AJAX Handler already initialized, skipping...');
        return;
    }
    isInitialized = true;

    // Form submit handler
    document.addEventListener('submit', (event) => {
        if (event.target.hasAttribute('data-ajax')) {
            handleFormSubmit(event);
        }
    });

    // Button/Link click handler
    document.addEventListener('click', (event) => {
        // پیدا کردن دکمه یا لینک با data-ajax
        const button = event.target.closest('button[data-ajax], a[data-ajax]');

        if (!button) return;

        if (button.type === 'submit') {
            const parentForm = button.closest('form[data-ajax]');
            if (parentForm) {
                return;
            }
        }

        event.preventDefault();
        event.stopPropagation();

        handleButtonClick({
            currentTarget: button,
            target: button,
            preventDefault: () => {},
            stopPropagation: () => {},
        });
    });
};

// Export built-in actions for reference
export { builtInActions };

// Make available globally for debugging
window.__ajaxHandler = {
    handleFormSubmit,
    handleButtonClick,
    getAjaxConfig,
    registerAction,
    getAction,
    executeAction,
    builtInActions,
    isInitialized: () => isInitialized,
    reset: () => { isInitialized = false; },
};
