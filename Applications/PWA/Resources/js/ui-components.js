/**
 * PWA UI Components Module
 *
 * Handles initialization and interaction for PWA-specific UI components
 */

export const uiComponents = {
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

  /**
   * Initialize Modals
   */
  initModals() {
    const modalTriggers = document.querySelectorAll('[data-modal-target]');
    const modalCloses = document.querySelectorAll('[data-modal-close]');

    modalTriggers.forEach((trigger) => {
      trigger.addEventListener('click', () => {
        const modalId = trigger.getAttribute('data-modal-target');
        const modal = document.querySelector(modalId);

        if (modal) {
          modal.classList.remove('hidden');
          document.body.style.overflow = 'hidden';
        }
      });
    });

    modalCloses.forEach((close) => {
      close.addEventListener('click', function () {
        const modal = this.closest('[data-modal]');

        if (modal) {
          modal.classList.add('hidden');
          document.body.style.overflow = '';
        }
      });
    });

    // Close modal on backdrop click
    document.addEventListener('click', (e) => {
      if (e.target.hasAttribute('data-modal')) {
        e.target.classList.add('hidden');
        document.body.style.overflow = '';
      }
    });
  },

  /**
   * Initialize Alerts
   */
  initAlerts() {
    const alerts = document.querySelectorAll('[data-alert]');

    alerts.forEach((alert) => {
      const closeBtn = alert.querySelector('[data-alert-close]');

      if (closeBtn) {
        closeBtn.addEventListener('click', () => {
          alert.classList.add('opacity-0', 'transition-opacity');

          setTimeout(() => {
            alert.remove();
          }, 300);
        });
      }

      // Auto-dismiss after 5 seconds
      const autoDismiss = alert.getAttribute('data-auto-dismiss');
      if (autoDismiss !== 'false') {
        setTimeout(() => {
          alert.classList.add('opacity-0', 'transition-opacity');

          setTimeout(() => {
            alert.remove();
          }, 300);
        }, 5000);
      }
    });
  },

  /**
   * Initialize Copy to Clipboard
   */
  initCopyToClipboard() {
    const copyButtons = document.querySelectorAll('[data-copy-target]');

    copyButtons.forEach((button) => {
      button.addEventListener('click', async () => {
        const targetSelector = button.getAttribute('data-copy-target');
        const targetElement = document.querySelector(targetSelector);

        if (!targetElement) {
          return;
        }

        const textToCopy = targetElement.textContent || targetElement.value;

        try {
          await navigator.clipboard.writeText(textToCopy);

          // Show success feedback
          const originalHTML = button.innerHTML;
          button.innerHTML = '<i class="fas fa-check"></i> کپی شد!';
          button.classList.add('text-success');

          setTimeout(() => {
            button.innerHTML = originalHTML;
            button.classList.remove('text-success');
          }, 2000);
        } catch (err) {
          console.error('Failed to copy:', err);
        }
      });
    });
  },
};
