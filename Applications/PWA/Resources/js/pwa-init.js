/**
 * PWA Initialization Module
 *
 * Handles service worker registration, install prompt, offline detection,
 * and other PWA-specific features
 */

let deferredPrompt;

export const pwaInit = {
  /**
   * Register Service Worker
   */
  registerServiceWorker() {
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker
          .register('/sw.js')
          .then((registration) => {
            console.log('✅ Service Worker registered:', registration.scope);

            // Check for updates periodically
            setInterval(() => {
              registration.update();
            }, 60000); // Check every minute
          })
          .catch((error) => {
            console.error('❌ Service Worker registration failed:', error);
          });
      });
    }
  },

  /**
   * Initialize Install Prompt
   */
  initInstallPrompt() {
    window.addEventListener('beforeinstallprompt', (e) => {
      // Prevent the mini-infobar from appearing on mobile
      e.preventDefault();
      // Stash the event so it can be triggered later
      deferredPrompt = e;
      // Show install button or prompt
      this.showInstallPromotion();
    });

    // Handle install button click
    const installButton = document.querySelector('#pwa-install-button');
    if (installButton) {
      installButton.addEventListener('click', async () => {
        if (!deferredPrompt) {
          return;
        }

        // Show the install prompt
        deferredPrompt.prompt();

        // Wait for the user to respond to the prompt
        const { outcome } = await deferredPrompt.userChoice;

        console.log(`User response to install prompt: ${outcome}`);

        // Clear the deferredPrompt
        deferredPrompt = null;

        // Hide the install promotion
        this.hideInstallPromotion();
      });
    }

    // Detect if app is installed
    window.addEventListener('appinstalled', () => {
      console.log('✅ PWA installed successfully!');
      this.hideInstallPromotion();
      deferredPrompt = null;
    });
  },

  /**
   * Show Install Promotion
   */
  showInstallPromotion() {
    const installPrompt = document.querySelector('#pwa-install-prompt');
    if (installPrompt) {
      installPrompt.classList.remove('hidden');
    }
  },

  /**
   * Hide Install Promotion
   */
  hideInstallPromotion() {
    const installPrompt = document.querySelector('#pwa-install-prompt');
    if (installPrompt) {
      installPrompt.classList.add('hidden');
    }
  },

  /**
   * Initialize Offline Detection
   */
  initOfflineDetection() {
    const updateOnlineStatus = () => {
      const offlineIndicator = document.querySelector('#offline-indicator');

      if (!offlineIndicator) {
        return;
      }

      if (navigator.onLine) {
        offlineIndicator.classList.add('hidden');
      } else {
        offlineIndicator.classList.remove('hidden');
      }
    };

    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);

    // Initial check
    updateOnlineStatus();
  },

  /**
   * Initialize Pull to Refresh
   */
  initPullToRefresh() {
    let startY = 0;
    let currentY = 0;
    let isPulling = false;

    const pullToRefreshElement = document.querySelector('#pull-to-refresh');
    if (!pullToRefreshElement) {
      return;
    }

    document.addEventListener('touchstart', (e) => {
      // Only trigger at top of page
      if (window.scrollY === 0) {
        startY = e.touches[0].pageY;
        isPulling = true;
      }
    });

    document.addEventListener('touchmove', (e) => {
      if (!isPulling) return;

      currentY = e.touches[0].pageY;
      const pullDistance = currentY - startY;

      if (pullDistance > 0 && pullDistance < 150) {
        pullToRefreshElement.style.transform = `translateY(${pullDistance}px)`;
      }

      if (pullDistance > 100) {
        pullToRefreshElement.classList.add('active');
      }
    });

    document.addEventListener('touchend', () => {
      if (!isPulling) return;

      const pullDistance = currentY - startY;

      if (pullDistance > 100) {
        // Trigger refresh
        window.location.reload();
      }

      pullToRefreshElement.style.transform = 'translateY(0)';
      pullToRefreshElement.classList.remove('active');

      isPulling = false;
      startY = 0;
      currentY = 0;
    });
  },
};
