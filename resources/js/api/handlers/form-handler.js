/**
 * Form Handler
 * Handles AJAX form submissions
 */

import { resolve } from '@/utils/di-container.js';
import { executeAction, executeActions } from '../actions/executor.js';

// Get dependencies from DI container (can be mocked in tests)
const getFormService = () => resolve('formService');
const getNotifications = () => resolve('notifications');

/**
 * Parse AJAX configuration from form attributes
 * @param {HTMLFormElement} form - Form element
 * @returns {object} Configuration object
 */
export const getFormConfig = (form) => {
  const action = form.getAttribute('action');
  const method =
    form.getAttribute('data-method') || form.getAttribute('method') || 'POST';

  // Parse response actions (comma or space separated)
  const actionsStr = form.getAttribute('data-on-success') || '';
  const actions = actionsStr
    .split(/[,\s]+/)
    .map((a) => a.trim())
    .filter((a) => a);

  // Get custom action name (for data-on-success="custom")
  const afterSuccessAction = form.getAttribute('custom-action');

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
 * Handle form submission
 * @param {Event} event - Submit event
 */
export const handleFormSubmit = async (event) => {
  const form = event.target;
  const submitButton =
    event.submitter ||
    form.querySelector(
      'button[type="submit"], button:not([type]), [type="submit"]',
    );

  // Don't prevent default for non-AJAX forms
  if (!form.hasAttribute('data-ajax')) {
    return;
  }

  event.preventDefault();

  // Get form configuration from attributes
  const config = getFormConfig(form);

  // Get dependencies
  const formService = getFormService();
  const notifications = getNotifications();

  // Validate if needed
  if (config.validate !== false) {
    if (!formService.validateForm(form)) {
      if (config.validationError) {
        notifications.showError(config.validationError);
      } else {
        notifications.showError('لطفاً تمام فیلدها را به درستی پر کنید');
      }
      return;
    }
  }

  // Prevent multiple concurrent AJAX requests globally
  if (window.__ajaxRequestInProgress) {
    return;
  }
  window.__ajaxRequestInProgress = true;

  // Show loading state if specified on form
  if (config.loadingClass) {
    addClasses(form, config.loadingClass);
  }

  // Add loading spinner to submit button
  const buttonWasDisabled = submitButton ? submitButton.disabled : false;
  if (submitButton) {
    submitButton.disabled = true;
    submitButton.classList.add('spinner-left');
  }

  try {
    // Submit form
    const result = await formService.submitForm(form, {
      method: config.method,
      route: config.action,
      showMessage: config.showMessage !== false,
      onValidationError: (validationResult) => {
        formService.displayValidationErrors(form, validationResult.errors);
      },
    });

    // If submission failed (network/server error), do not treat as success
    if (!result) {
      return;
    }

    // Handle validation errors
    if (result?.isValidationError) {
      return;
    }

    // Dispatch custom event
    form.dispatchEvent(
      new CustomEvent('ajax:success', {
        detail: { response: result, form },
      }),
    );

    // Execute response actions (await to keep global lock until done)
    if (config.actions.length > 0) {
      await executeActions(config.actions, result, form);
    } else if (config.defaultAction) {
      await executeAction(config.defaultAction, result, form);
    }
  } catch (error) {
    console.error('Form submission error:', error);

    // Dispatch error event
    form.dispatchEvent(
      new CustomEvent('ajax:error', {
        detail: { error, form },
      }),
    );

    // Execute error actions if specified
    if (config.errorActions.length > 0) {
      for (const actionName of config.errorActions) {
        executeAction(actionName, {}, form);
      }
    }
  } finally {
    // Remove loading state
    if (config.loadingClass) {
      removeClasses(form, config.loadingClass);
    }

    // Remove loading spinner from submit button
    if (submitButton) {
      submitButton.classList.remove('spinner-left');
      submitButton.disabled = buttonWasDisabled;
    }

    window.__ajaxRequestInProgress = false;
  }
};
