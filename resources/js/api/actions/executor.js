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
 * @returns {Promise<boolean>} True if action was executed
 */
export const executeAction = async (actionName, data, element) => {
  if (!actionName) return false;

  // Try built-in actions first
  const builtInHandler = builtInActions[actionName];
  if (builtInHandler) {
    try {
      const result = builtInHandler(data, element);
      if (result instanceof Promise) {
        await result;
      }
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
      const result = customHandler(data, element);
      if (result instanceof Promise) {
        await result;
      }
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
 * Execute multiple actions in sequence (await each one)
 * @param {string[]} actionNames - Array of action names
 * @param {object} data - Response data
 * @param {HTMLElement} element - Form or button element
 * @returns {Promise<void>}
 */
export const executeActions = async (actionNames, data, element) => {
  if (!Array.isArray(actionNames)) {
    actionNames = [actionNames];
  }

  for (const actionName of actionNames) {
    await executeAction(actionName, data, element);
  }
};
