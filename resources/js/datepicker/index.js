import {
  datepickerService,
  initDatepicker,
  destroyDatepicker,
  getDatepickerInstance,
  createDatepicker,
} from './service.js';

import { PersianDatepicker } from './persian-datepicker.js';
import { PersianDate, DateConverter } from './persian-date.js';
import { TimePicker } from './time-picker.js';

export {
  datepickerService,
  initDatepicker,
  destroyDatepicker,
  getDatepickerInstance,
  createDatepicker,
  PersianDatepicker,
  PersianDate,
  DateConverter,
  TimePicker,
};

document.addEventListener('modal:opened', (e) => {
  const modal = e.detail?.modal || e.target;
  if (modal) {
    initDatepicker(modal);
  }
});

document.addEventListener('content:loaded', (e) => {
  const container = e.detail?.container || document;
  initDatepicker(container);
});

// Convert Persian dates to Gregorian before form submission
document.addEventListener('submit', (e) => {
  const form = e.target;
  if (!form || form.tagName !== 'FORM') return;

  // Find all datepicker inputs in the form
  const datepickerInputs = form.querySelectorAll(
    '[data-datepicker], [data-datepicker-fa], [data-datepicker-start], [data-datepicker-end], [data-datepicker-middle], [data-datetimepicker], [data-datetimepicker-fa]',
  );

  datepickerInputs.forEach((input) => {
    const gregorianDate = input.getAttribute('data-gDate');
    if (gregorianDate) {
      // Store the Persian date in a temporary attribute for potential future use
      input.setAttribute('data-display-value', input.value);
      // Replace the input value with the Gregorian date for backend
      input.value = gregorianDate;
    }
  });

  // Restore Persian dates after a brief delay (for AJAX forms that prevent default)
  setTimeout(() => {
    datepickerInputs.forEach((input) => {
      const displayValue = input.getAttribute('data-display-value');
      if (displayValue) {
        input.value = displayValue;
        input.removeAttribute('data-display-value');
      }
    });
  }, 100);
});
