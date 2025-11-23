/**
 * User module specific logic for Planexx
 */
import { validation } from '@resources/js/validation.js';
import { notifications } from '@resources/js/notifications.js';
import { utilities } from '@resources/js/utilities.js';

const UserApp = {

  /**
   * Initialize user-specific functionality
   */
  init() {
    this.initUserEventListeners();
    this.initUserSpecificFeatures();
  },

  /**
   * User-specific event listeners
   */
  initUserEventListeners() {
    console.log('User module initialized');
  },

  /**
   * User-specific features (authentication, profile, etc.)
   */
  initUserSpecificFeatures() {
    // User-specific initialization code here
    // This is where user module specific logic would go
  },

  /**
   * Example of using global utilities in user module
   */
  handleUserAction() {
    // Use global validation
    if (validation.validateMobile('09123456789')) {
      notifications.showSuccess('شماره موبایل معتبر است');
    }

    // Use global utilities
    utilities.debounce(() => {
      console.log('Debounced user action');
    }, 500)();
  }

};

// Initialize user module when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  UserApp.init();
});
