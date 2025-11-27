/**
 * Bootstrap Application
 * Initialize core services and utilities
 */

// Initialize DI container first
import './bootstrap-di.js';

// Then initialize modules
import './api/index.js';
import './forms/index.js';
import './auth/index.js';
