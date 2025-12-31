/**
 * AdminPanel Main Application JavaScript Entry Point
 */
import '@shared-js/bootstrap.js';

// Import UI component initializers
import { uiComponents } from '@shared-js/ui-components.js';
import { forms } from '@shared-js/forms/index.js';
import { initPersianDigits } from '@shared-js/utils/persian-digits.js';
import { initTomSelect } from '@shared-js/tom-select/index.js';
import { initDatepicker } from '@shared-js/datepicker/index.js';

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

    forms.initForms();

    // Initialize Tom Select
    initTomSelect();

    // Initialize Datepicker
    initDatepicker();
  },
  { once: true },
);
