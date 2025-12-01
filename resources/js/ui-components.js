/**
 * Reusable UI components for Planexx
 */
export const uiComponents = {

  /**
   * Modal state storage
   */
  _modalStates: new Map(),

  /**
   * Open modal by ID with optional data
   * @param {string} modalId - Modal element ID
   * @param {object} data - Optional data to pass to modal
   */
  openModal(modalId, data = {}) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    this._modalStates.set(modalId, data);
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    const event = new CustomEvent('modal:opened', { 
      detail: { modalId, data },
      bubbles: true 
    });
    modal.dispatchEvent(event);
  },

  /**
   * Close modal by ID
   * @param {string} modalId - Modal element ID
   */
  closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    modal.classList.add('hidden');
    document.body.style.overflow = '';
    this._modalStates.delete(modalId);

    const event = new CustomEvent('modal:closed', { 
      detail: { modalId },
      bubbles: true 
    });
    modal.dispatchEvent(event);
  },

  /**
   * Get modal state data
   * @param {string} modalId - Modal element ID
   * @returns {object} Modal state data
   */
  getModalState(modalId) {
    return this._modalStates.get(modalId) || {};
  },

  /**
   * Initialize modal functionality
   */
  initModals() {
    // Open modal with data attributes
    document.querySelectorAll('[data-modal-open]').forEach(btn => {
      btn.addEventListener('click', (e) => {
        const modalId = e.currentTarget.dataset.modalOpen;
        const modalData = e.currentTarget.dataset.modalData 
          ? JSON.parse(e.currentTarget.dataset.modalData) 
          : {};
        
        this.openModal(modalId, modalData);
        
        const modal = document.getElementById(modalId);
        if (modal) {
          const event = new CustomEvent('modal:data-loaded', { 
            detail: modalData,
            bubbles: true 
          });
          modal.dispatchEvent(event);
        }
      });
    });

    // Close modal
    document.querySelectorAll('[data-modal-close]').forEach(btn => {
      btn.addEventListener('click', () => {
        const modal = btn.closest('[data-modal]');
        if (modal && modal.id) {
          this.closeModal(modal.id);
        }
      });
    });

    // Close on backdrop click
    document.querySelectorAll('[data-modal-backdrop]').forEach(backdrop => {
      backdrop.addEventListener('click', (e) => {
        if (e.target === backdrop) {
          const modal = backdrop.closest('[data-modal]');
          if (modal && modal.id) {
            this.closeModal(modal.id);
          }
        }
      });
    });

    // ESC key to close modals
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        document.querySelectorAll('[data-modal]:not(.hidden)').forEach(modal => {
          if (modal.id) {
            this.closeModal(modal.id);
          }
        });
      }
    });
  },

  /**
   * Initialize dropdown functionality
   */
  initDropdowns() {
    document.querySelectorAll('[data-dropdown-toggle]').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const dropdownId = btn.dataset.dropdownToggle;
        const dropdown = document.getElementById(dropdownId);

        if (dropdown) {
          // Close other dropdowns
          document.querySelectorAll('[data-dropdown]').forEach(d => {
            if (d !== dropdown) d.classList.add('hidden');
          });

          dropdown.classList.toggle('hidden');
        }
      });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
      if (!e.target.closest('[data-dropdown-toggle]')) {
        document.querySelectorAll('[data-dropdown]').forEach(d => {
          d.classList.add('hidden');
        });
      }
    });
  },

  /**
   * Show toast notification
   */
  showToast(message, type = 'info', duration = 3000) {
    const toast = document.createElement('div');
    const colors = {
      success: 'bg-green-500',
      error: 'bg-red-500',
      warning: 'bg-yellow-500',
      info: 'bg-blue-500'
    };

    toast.className = `fixed top-4 left-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity`;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = '0';
      setTimeout(() => toast.remove(), 300);
    }, duration);
  },

  /**
   * Initialize alert dismissal
   */
  initAlerts() {
    document.querySelectorAll('[data-alert-dismiss]').forEach(btn => {
      btn.addEventListener('click', () => {
        const alert = btn.closest('[data-alert]');
        if (alert) {
          alert.style.opacity = '0';
          setTimeout(() => alert.remove(), 300);
        }
      });
    });
  },

  /**
   * Initialize mobile menu
   */
  initMobileMenu() {
    const menuToggle = document.querySelector('[data-menu-toggle]');
    if (menuToggle) {
      menuToggle.addEventListener('click', () => {
        const menu = document.querySelector('[data-mobile-menu]');
        menu?.classList.toggle('hidden');
      });
    }
  },

  /**
   * Initialize mobile sidebar (dashboard)
   */
  initMobileSidebar() {
    const mobileToggle = document.querySelector('[data-mobile-sidebar-toggle]');
    const mobileSidebar = document.querySelector('[data-mobile-sidebar]');
    const mobileOverlay = document.querySelector('[data-mobile-sidebar-overlay]');
    const mobileClose = document.querySelector('[data-mobile-sidebar-close]');

    if (!mobileToggle || !mobileSidebar || !mobileOverlay) return;

    const openSidebar = () => {
      mobileSidebar.classList.remove('translate-x-full');
      mobileOverlay.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    };

    const closeSidebar = () => {
      mobileSidebar.classList.add('translate-x-full');
      mobileOverlay.classList.add('hidden');
      document.body.style.overflow = '';
    };

    mobileToggle.addEventListener('click', openSidebar);
    mobileClose?.addEventListener('click', closeSidebar);
    mobileOverlay.addEventListener('click', closeSidebar);
  },

  /**
   * Initialize user menu dropdown (header)
   */
  initUserMenu() {
    const userMenuToggle = document.querySelector('[data-user-menu-toggle]');
    const userMenu = document.querySelector('[data-user-menu]');

    if (!userMenuToggle || !userMenu) return;

    const toggleMenu = (e) => {
      e.stopPropagation();
      userMenu.classList.toggle('hidden');
    };

    const closeMenu = (e) => {
      if (!userMenuToggle.contains(e.target) && !userMenu.contains(e.target)) {
        userMenu.classList.add('hidden');
      }
    };

    userMenuToggle.addEventListener('click', toggleMenu);
    document.addEventListener('click', closeMenu);
  }

};
