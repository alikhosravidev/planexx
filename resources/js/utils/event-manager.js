/**
 * Event Manager
 * Centralized event listener management with automatic cleanup
 */

class EventManager {
  constructor() {
    this.listeners = new Map();
    this.listenerCounter = 0;
  }

  /**
   * Add event listener with automatic cleanup tracking
   * @param {HTMLElement} element
   * @param {string} event
   * @param {Function} handler
   * @param {object} options
   * @returns {string} Listener ID for manual removal
   */
  on(element, event, handler, options = {}) {
    if (!element) {
      console.warn('EventManager: Cannot add listener to null element');
      return null;
    }

    const listenerId = `listener_${++this.listenerCounter}`;

    element.addEventListener(event, handler, options);

    this.listeners.set(listenerId, {
      element,
      event,
      handler,
      options,
    });

    return listenerId;
  }

  /**
   * Remove specific event listener
   * @param {string} listenerId
   */
  off(listenerId) {
    const listener = this.listeners.get(listenerId);

    if (listener) {
      listener.element.removeEventListener(
        listener.event,
        listener.handler,
        listener.options,
      );
      this.listeners.delete(listenerId);
    }
  }

  /**
   * Event delegation helper
   * @param {HTMLElement} parent
   * @param {string} selector
   * @param {string} event
   * @param {Function} handler
   * @returns {string} Listener ID
   */
  delegate(parent, selector, event, handler) {
    const wrappedHandler = (e) => {
      const target = e.target.closest(selector);
      if (target) {
        handler.call(target, e);
      }
    };

    return this.on(parent, event, wrappedHandler);
  }

  /**
   * Remove all listeners for a specific element
   * @param {HTMLElement} element
   */
  offElement(element) {
    const toRemove = [];

    this.listeners.forEach((listener, id) => {
      if (listener.element === element) {
        toRemove.push(id);
      }
    });

    toRemove.forEach((id) => this.off(id));
  }

  /**
   * Remove all listeners
   */
  offAll() {
    this.listeners.forEach((listener, id) => {
      this.off(id);
    });
  }

  /**
   * Get count of active listeners
   * @returns {number}
   */
  getListenerCount() {
    return this.listeners.size;
  }

  /**
   * Dispatch custom event
   * @param {HTMLElement} element
   * @param {string} eventName
   * @param {object} detail
   * @param {boolean} bubbles
   */
  dispatch(element, eventName, detail = {}, bubbles = true) {
    if (element) {
      element.dispatchEvent(
        new CustomEvent(eventName, {
          detail,
          bubbles,
          cancelable: true,
        }),
      );
    }
  }
}

// Create global event manager instance
export const eventManager = new EventManager();

// Export convenience methods
export const on = (...args) => eventManager.on(...args);
export const off = (id) => eventManager.off(id);
export const delegate = (...args) => eventManager.delegate(...args);
export const offElement = (element) => eventManager.offElement(element);
export const offAll = () => eventManager.offAll();
export const dispatch = (...args) => eventManager.dispatch(...args);
