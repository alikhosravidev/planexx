/**
 * PWA Main Application JavaScript Entry Point
 */
import '@shared-js/bootstrap.js';

// Import PWA specific modules
import { pwaInit } from './pwa-init.js';
import { uiComponents } from '@shared-js/ui-components.js';
import { pwaUiComponents } from './pwa-ui-components.js';
import { forms } from '@shared-js/forms/index.js';
import { initPersianDigits } from '@shared-js/utils/persian-digits.js';
import { initTomSelect } from '@shared-js/tom-select/index.js';
import { initDatepicker } from '@shared-js/datepicker/index.js';
import { tooltipper } from '@shared-js/services/tooltiper.js';

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
    uiComponents.initBreadcrumbScroll();

    // Initialize PWA features
    pwaInit.registerServiceWorker();
    pwaInit.initInstallPrompt();
    pwaInit.initOfflineDetection();
    pwaInit.initPullToRefresh();

    // Initialize global UI components
    pwaUiComponents.initBottomNav();

    forms.initForms();

    // Initialize Tom Select (if needed for PWA)
    initTomSelect();

    // Initialize Datepicker (if needed for PWA)
    initDatepicker();

    // Auto-scroll workflow progress to current step
    const workflowContainer = document.getElementById('workflowProgress');
    const currentStep = document.getElementById('currentStep');

    if (workflowContainer && currentStep) {
      const containerWidth = workflowContainer.offsetWidth;
      const stepLeft = currentStep.offsetLeft;
      const stepWidth = currentStep.offsetWidth;
      const scrollPosition = stepLeft - (containerWidth / 2) + (stepWidth / 2);

      workflowContainer.scrollTo({
        left: scrollPosition,
        behavior: 'smooth',
      });
    }
  },
  { once: true },
);
