/**
 * PWA Main Application JavaScript Entry Point
 */
import '@shared-js/bootstrap.js';

// Import PWA specific modules
import { pwaInit } from './pwa-init.js';
import { uiComponents } from './ui-components.js';
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
    // Initialize PWA features
    pwaInit.registerServiceWorker();
    pwaInit.initInstallPrompt();
    pwaInit.initOfflineDetection();
    pwaInit.initPullToRefresh();

    // Initialize global UI components
    uiComponents.initBottomNav();
    uiComponents.initModals();
    uiComponents.initAlerts();
    uiComponents.initCopyToClipboard();

    forms.initForms();

    // Initialize Tom Select (if needed for PWA)
    initTomSelect();

    // Initialize Datepicker (if needed for PWA)
    initDatepicker();
  },
  { once: true },
);
