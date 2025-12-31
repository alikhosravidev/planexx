/**
 * PWA UI Components Module
 *
 * Handles initialization and interaction for PWA-specific UI components
 */

export const pwaUiComponents = {
  /**
   * Initialize Bottom Navigation
   */
  initBottomNav() {
    const navItems = document.querySelectorAll('.pwa-nav-item');
    const currentPath = window.location.pathname;

    navItems.forEach((item) => {
      const link = item.getAttribute('href');

      if (currentPath === link || currentPath.startsWith(link)) {
        item.classList.add('active');
      }

      // Add touch feedback
      item.addEventListener('touchstart', function () {
        this.style.opacity = '0.7';
      });

      item.addEventListener('touchend', function () {
        this.style.opacity = '1';
      });
    });
  },
};
