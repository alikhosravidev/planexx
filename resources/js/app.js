/**
 * Main Application JavaScript Entry Point
 */

// Bootstrap core services and AJAX handler
import './bootstrap';

// Import UI component initializers
import { uiComponents } from './ui-components.js';
import { forms } from './forms/index.js';

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
  // Initialize global UI components
  uiComponents.initModals();
  uiComponents.initDropdowns();
  uiComponents.initAlerts();
  uiComponents.initMobileMenu();

  // Initialize form functionality
  forms.initForms();
});
