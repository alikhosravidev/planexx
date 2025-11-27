/**
 * توابع کمکی و Utilities برای ساپل
 */
const Utils = {
  
  /**
   * Debounce برای جستجو و فیلتر
   */
  debounce(func, wait) {
    let timeout;
    return function(...args) {
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(this, args), wait);
    };
  },
  
  /**
   * اعتبارسنجی موبایل ایران
   */
  validateMobile(mobile) {
    const re = /^09\d{9}$/;
    return re.test(mobile);
  },
  
  /**
   * اعتبارسنجی کد OTP
   */
  validateOTP(otp) {
    return /^\d{4}$/.test(otp);
  },
  
  /**
   * فرمت عدد به فارسی
   */
  numberFormat(number) {
    return new Intl.NumberFormat('fa-IR').format(number);
  },
  
  /**
   * نمایش Toast/Alert
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
   * لود داده از JSON
   */
  async loadData(url) {
    try {
      const response = await fetch(url);
      if (!response.ok) throw new Error('Failed to load data');
      return await response.json();
    } catch (error) {
      console.error('Error loading data:', error);
      return null;
    }
  }
  
};
