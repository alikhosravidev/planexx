/**
 * منطق اصلی اپلیکیشن Planexx
 */
const App = {
  
  /**
   * مقداردهی اولیه
   */
  init() {
    this.initEventListeners();
    this.initModals();
    this.initDropdowns();
    this.initForms();
    this.initSearch();
    this.initAlerts();
    this.initMobileMenu();
  },
  
  /**
   * Event Listeners اصلی
   */
  initEventListeners() {
    console.log('App initialized');
  },
  
  /**
   * مدیریت Modal‌ها
   */
  initModals() {
    // باز کردن Modal
    document.querySelectorAll('[data-modal-open]').forEach(btn => {
      btn.addEventListener('click', (e) => {
        const modalId = e.currentTarget.dataset.modalOpen;
        const modal = document.getElementById(modalId);
        if (modal) {
          modal.classList.remove('hidden');
          document.body.style.overflow = 'hidden';
        }
      });
    });
    
    // بستن Modal
    document.querySelectorAll('[data-modal-close]').forEach(btn => {
      btn.addEventListener('click', () => {
        const modal = btn.closest('[data-modal]');
        if (modal) {
          modal.classList.add('hidden');
          document.body.style.overflow = '';
        }
      });
    });
    
    // بستن با کلیک روی Backdrop
    document.querySelectorAll('[data-modal-backdrop]').forEach(backdrop => {
      backdrop.addEventListener('click', (e) => {
        if (e.target === backdrop) {
          const modal = backdrop.closest('[data-modal]');
          if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
          }
        }
      });
    });
  },
  
  /**
   * مدیریت Dropdown‌ها
   */
  initDropdowns() {
    document.querySelectorAll('[data-dropdown-toggle]').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const dropdownId = btn.dataset.dropdownToggle;
        const dropdown = document.getElementById(dropdownId);
        
        if (dropdown) {
          // بستن سایر Dropdown‌ها
          document.querySelectorAll('[data-dropdown]').forEach(d => {
            if (d !== dropdown) d.classList.add('hidden');
          });
          
          dropdown.classList.toggle('hidden');
        }
      });
    });
    
    // بستن با کلیک خارج از Dropdown
    document.addEventListener('click', (e) => {
      if (!e.target.closest('[data-dropdown-toggle]')) {
        document.querySelectorAll('[data-dropdown]').forEach(d => {
          d.classList.add('hidden');
        });
      }
    });
  },
  
  /**
   * اعتبارسنجی فرم‌ها
   */
  initForms() {
    document.querySelectorAll('form[data-validate]').forEach(form => {
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        let isValid = true;
        const fields = form.querySelectorAll('[required], [data-validate]');
        
        fields.forEach(field => {
          field.classList.remove('border-red-500');
          
          // چک کردن فیلدهای خالی
          if (field.hasAttribute('required') && !field.value.trim()) {
            isValid = false;
            field.classList.add('border-red-500');
          }
          
          // اعتبارسنجی موبایل
          if (field.dataset.validate === 'mobile' && field.value && !Utils.validateMobile(field.value)) {
            isValid = false;
            field.classList.add('border-red-500');
          }
        });
        
        if (isValid) {
          // فرم معتبر است
          Utils.showToast('ورود موفقیت‌آمیز', 'success');
          // اینجا می‌توانید فرم را submit کنید
        } else {
          Utils.showToast('لطفاً تمام فیلدها را به درستی پر کنید', 'error');
        }
      });
    });
  },
  
  /**
   * جستجو و فیلتر
   */
  initSearch() {
    document.querySelectorAll('[data-search]').forEach(input => {
      input.addEventListener('input', Utils.debounce((e) => {
        const searchTerm = e.target.value.toLowerCase();
        const targetSelector = input.dataset.search;
        const items = document.querySelectorAll(targetSelector);
        
        items.forEach(item => {
          const text = item.textContent.toLowerCase();
          item.style.display = text.includes(searchTerm) ? '' : 'none';
        });
      }, 300));
    });
  },
  
  /**
   * مدیریت Alert‌ها
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
   * منوی موبایل
   */
  initMobileMenu() {
    const menuToggle = document.querySelector('[data-menu-toggle]');
    if (menuToggle) {
      menuToggle.addEventListener('click', () => {
        const menu = document.querySelector('[data-mobile-menu]');
        menu?.classList.toggle('hidden');
      });
    }
  }
  
};

// اجرا بعد از لود کامل صفحه
window.addEventListener('load', () => {
  App.init();
});
