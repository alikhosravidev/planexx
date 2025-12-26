import { DomUtils } from './dom-utils.js';
import { DateConverter } from './persian-date.js';

export class TimePicker {
  #datepicker;
  #container;
  #hourValue;
  #minuteValue;

  constructor(datepicker) {
    this.#datepicker = datepicker;
    this.#initializeTime();
  }

  #initializeTime() {
    const { options } = this.#datepicker;
    const now = new Date();

    this.#hourValue =
      options.defaultHour !== null ? options.defaultHour : now.getHours();
    this.#minuteValue =
      options.defaultMinute !== null
        ? options.defaultMinute
        : Math.floor(now.getMinutes() / options.minuteStep) *
          options.minuteStep;

    this.#hourValue = this.#clampHour(this.#hourValue);
    this.#minuteValue = this.#clampMinute(this.#minuteValue);
  }

  #clampHour(hour) {
    const { options } = this.#datepicker;
    let min = 0;
    let max = 23;

    if (options.minTime) {
      const [minH] = options.minTime.split(':').map(Number);
      min = minH;
    }
    if (options.maxTime) {
      const [maxH] = options.maxTime.split(':').map(Number);
      max = maxH;
    }

    return Math.max(min, Math.min(max, hour));
  }

  #clampMinute(minute) {
    const { options } = this.#datepicker;
    let min = 0;
    let max = 59;

    if (options.minTime) {
      const [minH, minM] = options.minTime.split(':').map(Number);
      if (this.#hourValue === minH) {
        min = minM || 0;
      }
    }
    if (options.maxTime) {
      const [maxH, maxM] = options.maxTime.split(':').map(Number);
      if (this.#hourValue === maxH) {
        max = maxM || 59;
      }
    }

    return Math.max(min, Math.min(max, minute));
  }

  render() {
    const { options } = this.#datepicker;

    this.#container = DomUtils.createElement('div', {
      className: 'pdp-timepicker',
    });

    const mainWrapper = DomUtils.createElement('div', {
      className: 'pdp-time-main',
    });

    const timeWrapper = DomUtils.createElement('div', {
      className: 'pdp-time-wrapper',
    });

    const hourSection = this.#createTimeSection(
      'hour',
      options.timeLabels.hour,
      this.#hourValue,
      0,
      23,
      options.hourStep,
    );
    const separator = DomUtils.createElement('div', {
      className: 'pdp-time-separator',
      html: ':',
    });
    const minuteSection = this.#createTimeSection(
      'minute',
      options.timeLabels.minute,
      this.#minuteValue,
      0,
      59,
      options.minuteStep,
    );

    DomUtils.append(timeWrapper, minuteSection);
    DomUtils.append(timeWrapper, separator);
    DomUtils.append(timeWrapper, hourSection);

    const actionsWrapper = DomUtils.createElement('div', {
      className: 'pdp-time-actions',
    });

    const nowBtn = DomUtils.createElement('button', {
      className: 'pdp-time-btn pdp-time-now',
      html: options.timeLabels.now,
      type: 'button',
    });

    DomUtils.on(nowBtn, 'click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      this.#setCurrentTime();
    });

    const confirmBtn = DomUtils.createElement('button', {
      className: 'pdp-time-btn pdp-time-confirm',
      html: options.timeLabels.confirm,
      type: 'button',
    });

    DomUtils.on(confirmBtn, 'click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      this.#confirmSelection();
    });

    DomUtils.append(actionsWrapper, nowBtn);
    DomUtils.append(actionsWrapper, confirmBtn);

    DomUtils.append(mainWrapper, timeWrapper);
    DomUtils.append(mainWrapper, actionsWrapper);
    DomUtils.append(this.#container, mainWrapper);

    return this.#container;
  }

  #createTimeSection(type, label, value, min, max, step) {
    const { options } = this.#datepicker;

    const section = DomUtils.createElement('div', {
      className: `pdp-time-section pdp-time-${type}`,
    });

    const labelEl = DomUtils.createElement('div', {
      className: 'pdp-time-label',
      html: label,
    });

    const controlWrapper = DomUtils.createElement('div', {
      className: 'pdp-time-control',
    });

    const upBtn = DomUtils.createElement('button', {
      className: 'pdp-time-arrow pdp-time-up',
      html: '▲',
      type: 'button',
    });

    const displayValue = options.persianNumbers
      ? DateConverter.toPersianDigits(String(value).padStart(2, '0'))
      : String(value).padStart(2, '0');

    const valueEl = DomUtils.createElement('div', {
      className: 'pdp-time-value',
      html: displayValue,
      'data-value': value,
    });

    const downBtn = DomUtils.createElement('button', {
      className: 'pdp-time-arrow pdp-time-down',
      html: '▼',
      type: 'button',
    });

    DomUtils.on(upBtn, 'click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      this.#incrementValue(type, step, valueEl);
    });

    DomUtils.on(downBtn, 'click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      this.#decrementValue(type, step, valueEl);
    });

    let wheelTimeout;
    DomUtils.on(
      controlWrapper,
      'wheel',
      (e) => {
        e.preventDefault();
        clearTimeout(wheelTimeout);
        wheelTimeout = setTimeout(() => {
          if (e.deltaY < 0) {
            this.#incrementValue(type, step, valueEl);
          } else {
            this.#decrementValue(type, step, valueEl);
          }
        }, 50);
      },
      { passive: false },
    );

    DomUtils.append(controlWrapper, upBtn);
    DomUtils.append(controlWrapper, valueEl);
    DomUtils.append(controlWrapper, downBtn);

    DomUtils.append(section, labelEl);
    DomUtils.append(section, controlWrapper);

    return section;
  }

  #incrementValue(type, step, valueEl) {
    const { options } = this.#datepicker;

    if (type === 'hour') {
      this.#hourValue = (this.#hourValue + step) % 24;
      this.#hourValue = this.#clampHour(this.#hourValue);
      this.#minuteValue = this.#clampMinute(this.#minuteValue);
      this.#updateMinuteDisplay();
    } else {
      this.#minuteValue = (this.#minuteValue + step) % 60;
      this.#minuteValue = this.#clampMinute(this.#minuteValue);
    }

    const newValue = type === 'hour' ? this.#hourValue : this.#minuteValue;
    const displayValue = options.persianNumbers
      ? DateConverter.toPersianDigits(String(newValue).padStart(2, '0'))
      : String(newValue).padStart(2, '0');

    valueEl.innerHTML = displayValue;
    valueEl.setAttribute('data-value', newValue);
  }

  #decrementValue(type, step, valueEl) {
    const { options } = this.#datepicker;

    if (type === 'hour') {
      this.#hourValue = (this.#hourValue - step + 24) % 24;
      this.#hourValue = this.#clampHour(this.#hourValue);
      this.#minuteValue = this.#clampMinute(this.#minuteValue);
      this.#updateMinuteDisplay();
    } else {
      this.#minuteValue = (this.#minuteValue - step + 60) % 60;
      this.#minuteValue = this.#clampMinute(this.#minuteValue);
    }

    const newValue = type === 'hour' ? this.#hourValue : this.#minuteValue;
    const displayValue = options.persianNumbers
      ? DateConverter.toPersianDigits(String(newValue).padStart(2, '0'))
      : String(newValue).padStart(2, '0');

    valueEl.innerHTML = displayValue;
    valueEl.setAttribute('data-value', newValue);
  }

  #updateMinuteDisplay() {
    if (!this.#container) return;

    const { options } = this.#datepicker;
    const minuteValueEl = this.#container.querySelector(
      '.pdp-time-minute .pdp-time-value',
    );

    if (minuteValueEl) {
      const displayValue = options.persianNumbers
        ? DateConverter.toPersianDigits(
            String(this.#minuteValue).padStart(2, '0'),
          )
        : String(this.#minuteValue).padStart(2, '0');

      minuteValueEl.innerHTML = displayValue;
      minuteValueEl.setAttribute('data-value', this.#minuteValue);
    }
  }

  #setCurrentTime() {
    const { options } = this.#datepicker;
    const now = new Date();

    this.#hourValue = this.#clampHour(now.getHours());
    this.#minuteValue = this.#clampMinute(
      Math.floor(now.getMinutes() / options.minuteStep) * options.minuteStep,
    );

    this.#updateDisplay();
  }

  #updateDisplay() {
    if (!this.#container) return;

    const { options } = this.#datepicker;

    const hourValueEl = this.#container.querySelector(
      '.pdp-time-hour .pdp-time-value',
    );
    const minuteValueEl = this.#container.querySelector(
      '.pdp-time-minute .pdp-time-value',
    );

    if (hourValueEl) {
      const displayHour = options.persianNumbers
        ? DateConverter.toPersianDigits(
            String(this.#hourValue).padStart(2, '0'),
          )
        : String(this.#hourValue).padStart(2, '0');
      hourValueEl.innerHTML = displayHour;
      hourValueEl.setAttribute('data-value', this.#hourValue);
    }

    if (minuteValueEl) {
      const displayMinute = options.persianNumbers
        ? DateConverter.toPersianDigits(
            String(this.#minuteValue).padStart(2, '0'),
          )
        : String(this.#minuteValue).padStart(2, '0');
      minuteValueEl.innerHTML = displayMinute;
      minuteValueEl.setAttribute('data-value', this.#minuteValue);
    }
  }

  #confirmSelection() {
    this.#datepicker.confirmDateTime(this.#hourValue, this.#minuteValue);
  }

  getTime() {
    return {
      hour: this.#hourValue,
      minute: this.#minuteValue,
      formatted: `${String(this.#hourValue).padStart(2, '0')}:${String(this.#minuteValue).padStart(2, '0')}`,
    };
  }

  setTime(hour, minute) {
    this.#hourValue = this.#clampHour(hour);
    this.#minuteValue = this.#clampMinute(minute);
    this.#updateDisplay();
  }

  getContainer() {
    return this.#container;
  }
}
