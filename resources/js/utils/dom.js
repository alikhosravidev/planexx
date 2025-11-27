/**
 * DOM Utilities
 * Centralized DOM manipulation helpers to reduce coupling
 */

/**
 * Add classes to element (handles space-separated classes)
 * @param {HTMLElement} element
 * @param {string} classString
 */
export const addClasses = (element, classString) => {
  if (!element || !classString) return;
  const classes = classString.split(/\s+/).filter((c) => c.trim());
  if (classes.length > 0) {
    element.classList.add(...classes);
  }
};

/**
 * Remove classes from element (handles space-separated classes)
 * @param {HTMLElement} element
 * @param {string} classString
 */
export const removeClasses = (element, classString) => {
  if (!element || !classString) return;
  const classes = classString.split(/\s+/).filter((c) => c.trim());
  if (classes.length > 0) {
    element.classList.remove(...classes);
  }
};

/**
 * Toggle classes on element
 * @param {HTMLElement} element
 * @param {string} classString
 */
export const toggleClasses = (element, classString) => {
  if (!element || !classString) return;
  const classes = classString.split(/\s+/).filter((c) => c.trim());
  classes.forEach((cls) => element.classList.toggle(cls));
};

/**
 * Get element by selector
 * @param {string} selector
 * @param {HTMLElement} context - Optional context element
 * @returns {HTMLElement|null}
 */
export const getElement = (selector, context = document) => {
  return context.querySelector(selector);
};

/**
 * Get all elements by selector
 * @param {string} selector
 * @param {HTMLElement} context - Optional context element
 * @returns {NodeList}
 */
export const getElements = (selector, context = document) => {
  return context.querySelectorAll(selector);
};

/**
 * Create element with attributes
 * @param {string} tag
 * @param {object} attributes
 * @param {string} content
 * @returns {HTMLElement}
 */
export const createElement = (tag, attributes = {}, content = '') => {
  const element = document.createElement(tag);
  
  Object.entries(attributes).forEach(([key, value]) => {
    if (key === 'class') {
      addClasses(element, value);
    } else {
      element.setAttribute(key, value);
    }
  });
  
  if (content) {
    element.textContent = content;
  }
  
  return element;
};

/**
 * Remove element from DOM
 * @param {HTMLElement} element
 */
export const removeElement = (element) => {
  if (element && element.parentNode) {
    element.parentNode.removeChild(element);
  }
};

/**
 * Show element (remove hidden class)
 * @param {HTMLElement} element
 */
export const showElement = (element) => {
  if (element) {
    element.classList.remove('hidden');
  }
};

/**
 * Hide element (add hidden class)
 * @param {HTMLElement} element
 */
export const hideElement = (element) => {
  if (element) {
    element.classList.add('hidden');
  }
};

/**
 * Toggle element visibility
 * @param {HTMLElement} element
 */
export const toggleElement = (element) => {
  if (element) {
    element.classList.toggle('hidden');
  }
};

/**
 * Dispatch custom event on element
 * @param {HTMLElement} element
 * @param {string} eventName
 * @param {object} detail
 * @param {boolean} bubbles
 */
export const dispatchEvent = (element, eventName, detail = {}, bubbles = true) => {
  if (element) {
    element.dispatchEvent(
      new CustomEvent(eventName, {
        detail,
        bubbles,
        cancelable: true,
      })
    );
  }
};

/**
 * Add event listener with cleanup tracking
 * @param {HTMLElement} element
 * @param {string} event
 * @param {Function} handler
 * @param {object} options
 * @returns {Function} Cleanup function
 */
export const addEventListener = (element, event, handler, options = {}) => {
  if (!element) return () => {};
  
  element.addEventListener(event, handler, options);
  
  // Return cleanup function
  return () => {
    element.removeEventListener(event, handler, options);
  };
};

/**
 * Event delegation helper
 * @param {HTMLElement} parent
 * @param {string} selector
 * @param {string} event
 * @param {Function} handler
 * @returns {Function} Cleanup function
 */
export const delegate = (parent, selector, event, handler) => {
  const wrappedHandler = (e) => {
    const target = e.target.closest(selector);
    if (target) {
      handler.call(target, e);
    }
  };
  
  return addEventListener(parent, event, wrappedHandler);
};
