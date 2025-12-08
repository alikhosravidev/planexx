/**
 * AJAX Handler - Declarative AJAX System Orchestrator
 * Coordinates form and button handlers with action execution
 * Zero manual JavaScript needed for form submissions!
 */

import { handleFormSubmit } from './handlers/form-handler.js';
import { handleButtonClick } from './handlers/button-handler.js';

export {
  registerAction,
  getAction,
  hasAction,
  unregisterAction,
  getRegisteredActions,
} from './actions/registry.js';
export { executeAction, executeActions } from './actions/executor.js';
export { builtInActions } from './actions/built-in.js';

let isInitialized = false;

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
    const button = event.target.closest('button[data-ajax], a[data-ajax]');

    if (!button) return;

    // Skip if it's a submit button inside an AJAX form
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
