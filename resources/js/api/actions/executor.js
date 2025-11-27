/**
 * Action Executor
 * Executes built-in and custom actions
 */

import { builtInActions } from './built-in.js';
import { getAction } from './registry.js';

/**
 * Execute response action
 * @param {string} actionName - Action name
 * @param {object} data - Response data
 * @param {HTMLElement} element - Form or button element
 * @returns {boolean} True if action was executed
 */
export const executeAction = (actionName, data, element) => {
  if (!actionName) return false;

  // Try built-in actions first
  const builtInHandler = builtInActions[actionName];
  if (builtInHandler) {
    try {
      builtInHandler(data, element);
      return true;
    } catch (error) {
      console.error(`Error executing built-in action "${actionName}":`, error);
      return false;
    }
  }

  // Try custom registered actions
  const customHandler = getAction(actionName);
  if (customHandler) {
    try {
      customHandler(data, element);
      return true;
    } catch (error) {
      console.error(`Error executing custom action "${actionName}":`, error);
      return false;
    }
  }

  console.warn(`Unknown action: "${actionName}"`);
  return false;
};

/**
 * Execute multiple actions in sequence
 * @param {string[]} actionNames - Array of action names
 * @param {object} data - Response data
 * @param {HTMLElement} element - Form or button element
 */
export const executeActions = (actionNames, data, element) => {
  if (!Array.isArray(actionNames)) {
    actionNames = [actionNames];
  }

  actionNames.forEach((actionName) => {
    executeAction(actionName, data, element);
  });
};
