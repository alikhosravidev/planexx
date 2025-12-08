/**
 * Simple Dependency Injection Container
 * Enables testability by allowing dependency injection
 */

class DIContainer {
  constructor() {
    this.services = new Map();
    this.singletons = new Map();
  }

  /**
   * Register a service factory
   * @param {string} name - Service name
   * @param {Function} factory - Factory function
   * @param {boolean} singleton - Whether to cache instance
   */
  register(name, factory, singleton = true) {
    this.services.set(name, { factory, singleton });
  }

  /**
   * Get service instance
   * @param {string} name - Service name
   * @returns {*} Service instance
   */
  get(name) {
    const service = this.services.get(name);

    if (!service) {
      throw new Error(`Service "${name}" not registered`);
    }

    // Return cached singleton if exists
    if (service.singleton && this.singletons.has(name)) {
      return this.singletons.get(name);
    }

    // Create new instance
    const instance = service.factory(this);

    // Cache if singleton
    if (service.singleton) {
      this.singletons.set(name, instance);
    }

    return instance;
  }

  /**
   * Check if service is registered
   * @param {string} name
   * @returns {boolean}
   */
  has(name) {
    return this.services.has(name);
  }

  /**
   * Override service (useful for testing)
   * @param {string} name
   * @param {*} instance
   */
  override(name, instance) {
    this.singletons.set(name, instance);
  }

  /**
   * Clear all singletons (useful for testing)
   */
  clearSingletons() {
    this.singletons.clear();
  }

  /**
   * Reset container
   */
  reset() {
    this.services.clear();
    this.singletons.clear();
  }
}

// Create global container instance
export const container = new DIContainer();

/**
 * Helper to get service from container
 * @param {string} name
 * @returns {*}
 */
export const resolve = (name) => container.get(name);

/**
 * Helper to register service
 * @param {string} name
 * @param {Function} factory
 * @param {boolean} singleton
 */
export const register = (name, factory, singleton = true) => {
  container.register(name, factory, singleton);
};
