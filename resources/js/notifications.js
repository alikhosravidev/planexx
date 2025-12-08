/**
 * Notification system for Planexx
 */
export const notifications = {
  /**
   * Ensure a container exists for stacking toasts with spacing
   */
  getContainer() {
    let container = document.querySelector('[data-toast-container]');
    if (!container) {
      container = document.createElement('div');
      container.setAttribute('data-toast-container', 'true');
      container.className =
        'fixed top-4 left-4 flex flex-col gap-3 z-50 pointer-events-none';
      document.body.appendChild(container);
    }
    return container;
  },

  /**
   * Show toast notification
   * @param {string} message - Message to display
   * @param {string} type - Type of notification (success, error, warning, info)
   * @param {number} duration - Duration in milliseconds
   */
  showToast(message, type = 'info', duration = 3000) {
    const container = this.getContainer();
    const toast = document.createElement('div');
    const colors = {
      success: 'bg-green-500',
      error: 'bg-red-500',
      warning: 'bg-yellow-500',
      info: 'bg-blue-500',
    };

    toast.className = `${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg pointer-events-auto transition-opacity`;
    toast.textContent = message;
    container.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = '0';
      setTimeout(() => toast.remove(), 300);
    }, duration);
  },

  /**
   * Show success toast
   * @param {string} message - Success message
   * @param {number} duration - Duration in milliseconds
   */
  showSuccess(message, duration = 3000) {
    this.showToast(message, 'success', duration);
  },

  /**
   * Show error toast
   * @param {string} message - Error message
   * @param {number} duration - Duration in milliseconds
   */
  showError(message, duration = 3000) {
    this.showToast(message, 'error', duration);
  },

  /**
   * Show warning toast
   * @param {string} message - Warning message
   * @param {number} duration - Duration in milliseconds
   */
  showWarning(message, duration = 3000) {
    this.showToast(message, 'warning', duration);
  },

  /**
   * Show info toast
   * @param {string} message - Info message
   * @param {number} duration - Duration in milliseconds
   */
  showInfo(message, duration = 3000) {
    this.showToast(message, 'info', duration);
  },
};
