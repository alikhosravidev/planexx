/**
 * Dependency Injection Bootstrap
 * Register all services in the DI container
 */

import { register } from './utils/di-container.js';
import { httpClient } from './api/http-client.js';
import { formService } from './services/form-service.js';
import { notifications } from './notifications.js';

/**
 * Register core services
 */
export const bootstrapDI = () => {
  // HTTP Client
  register('httpClient', () => httpClient, true);

  // Form Service
  register('formService', () => formService, true);

  // Notifications
  register('notifications', () => notifications, true);

  // Ziggy route helper
  register('route', () => window.route, true);
};

// Auto-bootstrap on import
bootstrapDI();
