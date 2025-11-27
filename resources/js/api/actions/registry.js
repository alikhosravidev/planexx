/**
 * Action Registry
 * Central registry for custom AJAX response actions
 */

const actionRegistry = new Map();

/**
 * Register a custom action
 * @param {string} name - Action name
 * @param {Function} handler - Action handler function (data, element) => void
 */
export const registerAction = (name, handler) => {
  if (typeof handler !== 'function') {
    throw new TypeError(`Action handler for "${name}" must be a function`);
  }
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
 * Check if action is registered
 * @param {string} name - Action name
 * @returns {boolean}
 */
export const hasAction = (name) => {
  return actionRegistry.has(name);
};

/**
 * Unregister an action
 * @param {string} name - Action name
 */
export const unregisterAction = (name) => {
  actionRegistry.delete(name);
};

/**
 * Get all registered action names
 * @returns {string[]}
 */
export const getRegisteredActions = () => {
  return Array.from(actionRegistry.keys());
};
