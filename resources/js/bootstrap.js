/**
 * Bootstrap Application
 * Initialize core services and utilities
 */

// Initialize DI container first
import './bootstrap-di.js';

// Then initialize core modules (api, auth are available globally)
import './api/index.js';
import './auth/index.js';
