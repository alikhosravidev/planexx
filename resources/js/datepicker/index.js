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
