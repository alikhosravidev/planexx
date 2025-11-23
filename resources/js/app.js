// Main application JavaScript
import './bootstrap';

// Import utility modules
import { uiComponents } from './ui-components.js';
import { forms } from './forms.js';

// Initialize global components
document.addEventListener('DOMContentLoaded', () => {
  // Initialize UI components
  uiComponents.initModals();
  uiComponents.initDropdowns();
  uiComponents.initAlerts();
  uiComponents.initMobileMenu();

  // Initialize form functionality
  forms.initFormValidation();
  forms.initSearch();
  forms.initOTPInputs();
  forms.initBackButton();
});

// Import core module assets dynamically
const coreModules = [];

coreModules.forEach(module => {
    // Try to import module-specific CSS (silently skip if not found)
    import(`../../app/Core/${module}/Resources/assets/css/${module.toLowerCase()}.css`)
        .catch(() => {}); // Silently ignore if CSS file doesn't exist

    // Try to import module-specific JS
    import(`../../app/Core/${module}/Resources/assets/js/${module.toLowerCase()}.js`)
        .catch(() => console.log(`No JS found for module: ${module}`));
});

// Import module assets dynamically
const modules = [];

modules.forEach(module => {
    // Try to import module-specific CSS
    import(`../../Modules/${module}/Resources/assets/css/${module.toLowerCase()}.css`)
        .catch(() => console.log(`No CSS found for module: ${module}`));

    // Try to import module-specific JS
    import(`../../Modules/${module}/Resources/assets/js/${module.toLowerCase()}.js`)
        .catch(() => console.log(`No JS found for module: ${module}`));
});
