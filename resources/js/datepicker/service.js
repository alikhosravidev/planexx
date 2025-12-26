import { PersianDatepicker } from './persian-datepicker.js';
import { SELECTORS, DATA_ATTRIBUTES } from './config.js';

class DatepickerService {
  #instances = new Map();

  init(container = document) {
    this.#initStandard(container);
    this.#initPersian(container);
    this.#initStart(container);
    this.#initEnd(container);
    this.#initMiddle(container);
    this.#initDatetime(container);
    this.#initDatetimeFa(container);
    this.#initTimepicker(container);
  }

  #initStandard(container) {
    container.querySelectorAll(SELECTORS.datepicker).forEach((el) => {
      if (this.#instances.has(el)) return;
      this.#createDatepicker(el, {
        formatDate: 'YYYY-0M-0D',
        cellWidth: 40,
        cellHeight: 40,
        fontSize: 16,
        showGregorianDate: true,
      });
    });
  }

  #initPersian(container) {
    container.querySelectorAll(SELECTORS.datepickerFa).forEach((el) => {
      if (this.#instances.has(el)) return;
      this.#createDatepicker(el, {
        formatDate: 'YYYY-0M-0D',
        cellWidth: 40,
        cellHeight: 40,
        fontSize: 16,
        showGregorianDate: false,
        onSelect: () => {
          const parentForm = el.closest('form');
          if (
            parentForm &&
            parentForm.getAttribute('data-jsc') === 'submit-onChange'
          ) {
            parentForm.submit();
          }
        },
      });
    });
  }

  #initStart(container) {
    container.querySelectorAll(SELECTORS.datepickerStart).forEach((el) => {
      if (this.#instances.has(el)) return;
      this.#createDatepicker(el, {
        formatDate: 'YYYY-0M-0D 00:00:00',
        cellWidth: 40,
        cellHeight: 40,
        fontSize: 16,
        showGregorianDate: true,
      });
    });
  }

  #initEnd(container) {
    container.querySelectorAll(SELECTORS.datepickerEnd).forEach((el) => {
      if (this.#instances.has(el)) return;
      this.#createDatepicker(el, {
        formatDate: 'YYYY-0M-0D 23:59:59',
        cellWidth: 40,
        cellHeight: 40,
        fontSize: 16,
        showGregorianDate: true,
      });
    });
  }

  #initMiddle(container) {
    container.querySelectorAll(SELECTORS.datepickerMiddle).forEach((el) => {
      if (this.#instances.has(el)) return;
      this.#createDatepicker(el, {
        formatDate: 'YYYY-0M-0D 12:00:00',
        cellWidth: 40,
        cellHeight: 40,
        fontSize: 16,
        showGregorianDate: true,
      });
    });
  }

  #initDatetime(container) {
    container.querySelectorAll(SELECTORS.datetimepicker).forEach((el) => {
      if (this.#instances.has(el)) return;
      this.#createDatepicker(el, {
        formatDate: 'YYYY-0M-0D 0h:0m',
        cellWidth: 40,
        cellHeight: 40,
        fontSize: 16,
        showGregorianDate: true,
        showTimePicker: true,
      });
    });
  }

  #initDatetimeFa(container) {
    container.querySelectorAll(SELECTORS.datetimepickerFa).forEach((el) => {
      if (this.#instances.has(el)) return;
      this.#createDatepicker(el, {
        formatDate: 'YYYY-0M-0D 0h:0m',
        cellWidth: 40,
        cellHeight: 40,
        fontSize: 16,
        showGregorianDate: false,
        showTimePicker: true,
      });
    });
  }

  #initTimepicker(container) {
    container.querySelectorAll(SELECTORS.timepicker).forEach((el) => {
      if (this.#instances.has(el)) return;
      this.#createDatepicker(el, {
        formatDate: '0h:0m',
        cellWidth: 40,
        cellHeight: 40,
        fontSize: 16,
        timePickerOnly: true,
        showTimePicker: true,
      });
    });
  }

  #createDatepicker(el, defaultOptions) {
    const customOptions = this.#getOptionsFromAttributes(el);
    const options = { ...defaultOptions, ...customOptions };

    el.setAttribute('autocomplete', 'off');

    const instance = new PersianDatepicker(el, options);
    this.#instances.set(el, instance);

    return instance;
  }

  #getOptionsFromAttributes(el) {
    const options = {};

    const format = el.getAttribute(DATA_ATTRIBUTES.format);
    if (format) options.formatDate = format;

    const gregorian = el.getAttribute(DATA_ATTRIBUTES.gregorian);
    if (gregorian !== null)
      options.showGregorianDate = gregorian === 'true' || gregorian === '1';

    const minDate = el.getAttribute(DATA_ATTRIBUTES.minDate);
    if (minDate) options.startDate = minDate;

    const maxDate = el.getAttribute(DATA_ATTRIBUTES.maxDate);
    if (maxDate) options.endDate = maxDate;

    const defaultValue = el.getAttribute(DATA_ATTRIBUTES.defaultValue);
    if (defaultValue) {
      options.selectedDate = defaultValue;
      options.selectedBefore = true;
    }

    const showTime = el.getAttribute(DATA_ATTRIBUTES.showTime);
    if (showTime !== null)
      options.showTimePicker = showTime === 'true' || showTime === '1';

    const timeOnly = el.getAttribute(DATA_ATTRIBUTES.timeOnly);
    if (timeOnly !== null)
      options.timePickerOnly = timeOnly === 'true' || timeOnly === '1';

    const hourStep = el.getAttribute(DATA_ATTRIBUTES.hourStep);
    if (hourStep) options.hourStep = parseInt(hourStep, 10);

    const minuteStep = el.getAttribute(DATA_ATTRIBUTES.minuteStep);
    if (minuteStep) options.minuteStep = parseInt(minuteStep, 10);

    const minTime = el.getAttribute(DATA_ATTRIBUTES.minTime);
    if (minTime) options.minTime = minTime;

    const maxTime = el.getAttribute(DATA_ATTRIBUTES.maxTime);
    if (maxTime) options.maxTime = maxTime;

    return options;
  }

  getInstance(el) {
    return this.#instances.get(el) || null;
  }

  destroy(el) {
    const instance = this.#instances.get(el);
    if (instance) {
      instance.destroy();
      this.#instances.delete(el);
    }
  }

  destroyAll(container = document) {
    container.querySelectorAll('input').forEach((el) => {
      this.destroy(el);
    });
  }

  refresh(el) {
    const instance = this.#instances.get(el);
    if (instance) {
      instance.refresh();
    }
  }

  create(el, options = {}) {
    if (this.#instances.has(el)) {
      return this.#instances.get(el);
    }

    el.setAttribute('autocomplete', 'off');
    const instance = new PersianDatepicker(el, options);
    this.#instances.set(el, instance);

    return instance;
  }
}

export const datepickerService = new DatepickerService();

export function initDatepicker(container = document) {
  datepickerService.init(container);
}

export function destroyDatepicker(el) {
  datepickerService.destroy(el);
}

export function getDatepickerInstance(el) {
  return datepickerService.getInstance(el);
}

export function createDatepicker(el, options = {}) {
  return datepickerService.create(el, options);
}
