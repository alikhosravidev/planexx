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
   * Initialize modal forms
   */
  initModalForms() {
    document.querySelectorAll('[data-modal-form]').forEach(form => {
      form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const modalId = form.closest('[data-modal]')?.id;
        const url = form.dataset.url || form.action;
        const method = form.dataset.method || form.method || 'POST';
        const redirect = form.dataset.redirect;
        
        if (!url || url === '#') {
          console.error('No valid URL specified for modal form');
          return;
        }
        
        const formData = new FormData(form);
        const data = {};
        
        for (const [key, value] of formData.entries()) {
          if (key.endsWith('[]')) {
            const cleanKey = key.slice(0, -2);
            if (!data[cleanKey]) {
              data[cleanKey] = [];
            }
            data[cleanKey].push(value);
          } else {
            data[key] = value;
          }
        }
        
        const modalState = this.getModalState(modalId);
        if (modalState.userId) {
          data.user_id = modalState.userId;
        }
        
        try {
          const response = await fetch(url, {
            method: method,
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
          });
          
          const result = await response.json();
          
          if (response.ok) {
            this.showToast(result.message || 'عملیات با موفقیت انجام شد', 'success');
            
            if (modalId) {
              this.closeModal(modalId);
            }
            
            if (redirect) {
              setTimeout(() => {
                if (redirect === 'reload') {
                  window.location.reload();
                } else {
                  window.location.href = redirect;
                }
              }, 1000);
            }
          } else {
            this.showToast(result.message || 'خطایی رخ داد', 'error');
          }
        } catch (error) {
          console.error('Form submission error:', error);
          this.showToast('خطایی در ارسال فرم رخ داد', 'error');
        }
      });
    });
  },

  /**
   * Initialize action buttons (delete, confirm, etc.)
   */
  initActionButtons() {
    document.querySelectorAll('[data-action]').forEach(btn => {
      btn.addEventListener('click', async (e) => {
        e.preventDefault();
        
        const action = btn.dataset.action;
        const confirmMsg = btn.dataset.confirm;
        const url = btn.dataset.url;
        const method = btn.dataset.method || 'DELETE';
        const redirect = btn.dataset.redirect;
        
        if (confirmMsg && !window.confirm(confirmMsg)) {
          return;
        }
        
        if (!url) {
          console.error('No URL specified for action button');
          return;
        }
        
        try {
          const response = await fetch(url, {
            method: method,
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            }
          });
          
          const data = await response.json();
          
          if (response.ok) {
            if (data.message) {
              this.showToast(data.message, 'success');
            }
            
            if (redirect) {
              setTimeout(() => {
                if (redirect === 'reload') {
                  window.location.reload();
                } else {
                  window.location.href = redirect;
                }
              }, 1000);
            }
          } else {
            this.showToast(data.message || 'خطایی رخ داد', 'error');
          }
        } catch (error) {
          console.error('Action error:', error);
          this.showToast('خطایی در انجام عملیات رخ داد', 'error');
        }
      });
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
