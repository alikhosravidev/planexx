/**
 * Main Application JavaScript Entry Point
 */

// Bootstrap core services and AJAX handler
import './bootstrap';

// Import UI component initializers
import { uiComponents } from './ui-components.js';
import { forms } from './forms/index.js';
import './pages/documents.js';
import { initPersianDigits } from './utils/persian-digits.js';

// Initialize Persian digits conversion with configurable skip classes
initPersianDigits({
  skipClasses: ['phpdebugbar'],
});

// Initialize on DOM ready
document.addEventListener(
  'DOMContentLoaded',
  () => {
    // Initialize global UI components
    uiComponents.initModals();
    uiComponents.initDropdowns();
    uiComponents.initAlerts();
    uiComponents.initMobileMenu();
    uiComponents.initMobileSidebar();
    uiComponents.initUserMenu();

    // Initialize form functionality
    forms.initForms();
  },
  { once: true },
);
