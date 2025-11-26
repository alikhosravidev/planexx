/**
 * Bootstrap Application
 * Initialize core services and AJAX handler
 */

// Import AJAX infrastructure (single entrypoint)
import './api/index.js';

// Import Forms infrastructure (single entrypoint)
import './forms/index.js';

// Import Auth (single entrypoint: actions + otp + ui)
import './auth/index.js';
