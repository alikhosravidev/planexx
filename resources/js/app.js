/**
 * Main Application JavaScript Entry Point
 */

// Bootstrap core services and AJAX handler
import './bootstrap';

// Import UI component initializers
import { uiComponents } from './ui-components.js';
import { forms } from './forms/index.js';
import { initPersianDigits } from './utils/persian-digits.js';
import { initTomSelect } from './tom-select/index.js';

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
    uiComponents.initCopyToClipboard();

    // Initialize form functionality
    forms.initForms();

    // Initialize Tom Select
    initTomSelect();
  },
  { once: true },
);
