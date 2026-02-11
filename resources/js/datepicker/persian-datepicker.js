import { DEFAULT_OPTIONS } from './config.js';
import {
  PersianDate,
  DateConverter,
  formatGregorianDate,
} from './persian-date.js';
import { DomUtils } from './dom-utils.js';
import { CalendarRenderer } from './calendar-renderer.js';
import { PositionManager } from './position-manager.js';
import { TimePicker } from './time-picker.js';

export class PersianDatepicker {
  #id;
  #renderer;
  #positionManager;
  #timePicker;
  #documentClickHandler;
  #blurHandler;
  #selectedDateTemp;

  element;
  calendar;
  options;
  persianDate;
  hour;
  minute;

  constructor(element, userOptions = {}) {
    this.element = element;
    this.options = { ...DEFAULT_OPTIONS, ...userOptions };
    this.#id = `pdp-${Math.round(Math.random() * 1e7)}`;

    this.#initializeDate();
    this.#initializeTime();
    this.#createCalendar();
    this.#setupEventListeners();

    this.#renderer = new CalendarRenderer(this);
    this.#positionManager = new PositionManager(this);

    if (this.options.showTimePicker || this.options.timePickerOnly) {
      this.#timePicker = new TimePicker(this);
    }

    if (this.options.selectedBefore) {
      this.#showInitialDate();
    }

    document.body.appendChild(this.calendar);
    this.#renderer.render();
    this.#positionManager.init();

    if (!this.options.alwaysShow) {
      DomUtils.hide(this.calendar);
    }
  }

  #initializeDate() {
    const { options } = this;

    if (options.startDate !== null) {
      if (options.startDate === 'today') {
        options.startDate = PersianDate.now().toString('YYYY/MM/DD');
      }
      if (options.endDate === 'today') {
        options.endDate = PersianDate.now().toString('YYYY/MM/DD');
      }
      options.selectedDate = options.startDate;
    }

    if (options.selectedDate === null && !options.showGregorianDate) {
      const datePattern =
        /^([1-9][0-9]{3})\/([0]?[1-9]|[1][0-2])\/([0]?[1-9]|[1-2][0-9]|[3][0-1])$/;
      const elementValue = DomUtils.getValue(this.element);

      if (datePattern.test(elementValue)) {
        options.selectedDate = elementValue;
      }
    }

    this.persianDate =
      options.selectedDate !== null
        ? PersianDate.parse(options.selectedDate)
        : PersianDate.now();

    if (
      options.selectableYears &&
      options.selectableYears.indexOf(this.persianDate.year) === -1
    ) {
      this.persianDate.year = options.selectableYears[0];
    }

    if (options.selectableMonths.indexOf(this.persianDate.month) === -1) {
      this.persianDate.month = options.selectableMonths[0];
    }

    this.persianDate.formatDate = options.formatDate;

    if (options.startDate !== null) {
      options.selectableYears = [];
      const startYear = PersianDate.parse(options.startDate).year;
      const endYear = PersianDate.parse(options.endDate).year;

      for (let i = startYear; i <= endYear; i++) {
        options.selectableYears.push(i);
      }
    }
  }

  #initializeTime() {
    const { options } = this;
    const now = new Date();

    this.hour =
      options.defaultHour !== null ? options.defaultHour : now.getHours();
    this.minute =
      options.defaultMinute !== null
        ? options.defaultMinute
        : Math.floor(now.getMinutes() / options.minuteStep) *
          options.minuteStep;
  }

  #createCalendar() {
    this.calendar = DomUtils.createElement('div', {
      id: this.#id,
      className: `pdp-${this.options.theme}`,
    });
  }

  #setupEventListeners() {
    const { element, options } = this;

    if (!element.getAttribute('pdp-id')) {
      element.setAttribute('pdp-id', this.#id);
    }

    DomUtils.addClass(element, 'pdp-el');

    DomUtils.on(element, 'click', () => this.show());
    DomUtils.on(element, 'focus', () => this.show());

    if (options.closeOnBlur) {
      this.#blurHandler = () => {
        if (!this.#isMouseOverCalendar()) {
          this.hide();
        }
      };
      DomUtils.on(element, 'blur', this.#blurHandler);
    }

    this.#documentClickHandler = (e) => {
      const target = e.target;

      if (
        !element.contains(target) &&
        !this.calendar.contains(target) &&
        DomUtils.isVisible(this.calendar)
      ) {
        this.hide();
      }

      const yearSelect = this.calendar.querySelector('.yearSelect');
      if (yearSelect && !yearSelect.contains(target)) {
        DomUtils.hide(yearSelect);
      }

      const monthSelect = this.calendar.querySelector('.monthSelect');
      if (monthSelect && !monthSelect.contains(target)) {
        DomUtils.hide(monthSelect);
      }
    };

    document.addEventListener('mouseup', this.#documentClickHandler);

    if (options.isRTL) {
      DomUtils.addClass(element, 'rtl');
    }
  }

  #isMouseOverCalendar() {
    return this.calendar.matches(':hover');
  }

  #showInitialDate() {
    const { options } = this;

    if (options.selectedDate !== null) {
      const pd = PersianDate.parse(options.selectedDate);
      const jDate = pd.toString('YYYY/MM/DD/' + DateConverter.getWeekday(pd));
      // Use the preserved gDate from the parsed date to maintain time information
      const gDate = pd.gDate;
      this.showDate(jDate, gDate, options.showGregorianDate);
    } else {
      const now = PersianDate.now();
      const jDate = now.toString('YYYY/MM/DD/' + DateConverter.getWeekday(now));
      const gDate = now.gDate;
      this.showDate(jDate, gDate, options.showGregorianDate);
    }
  }

  show() {
    DomUtils.show(this.calendar);

    document.querySelectorAll('.pdp-el').forEach((el) => {
      if (el !== this.element) {
        const otherId = el.getAttribute('pdp-id');
        const otherCalendar = document.getElementById(otherId);
        if (otherCalendar && DomUtils.isVisible(otherCalendar)) {
          DomUtils.hide(otherCalendar);
        }
      }
    });

    this.options.onShow(this.calendar);
    this.#positionManager.updatePosition();
  }

  hide() {
    this.options.onHide(this.calendar);

    if (!this.options.alwaysShow) {
      DomUtils.hide(this.calendar);
    }
  }

  selectDate(jDate, gDate) {
    this.calendar
      .querySelectorAll('.day')
      .forEach((d) => DomUtils.removeClass(d, 'selday'));

    const clickedCell = this.calendar.querySelector(`[data-jdate="${jDate}"]`);
    if (clickedCell) {
      DomUtils.addClass(clickedCell, 'selday');
    }

    if (this.options.showTimePicker) {
      this.#selectedDateTemp = { jDate, gDate };
    } else {
      this.showDate(jDate, gDate, this.options.showGregorianDate);
      this.hide();
    }
  }

  confirmDateTime(hour, minute) {
    this.hour = hour;
    this.minute = minute;

    const { jDate, gDate } = this.#selectedDateTemp || {
      jDate: this.persianDate.toString('YYYY/MM/DD'),
      gDate: DateConverter.jalaliToGregorian(this.persianDate),
    };

    this.showDateWithTime(
      jDate,
      gDate,
      hour,
      minute,
      this.options.showGregorianDate,
    );
    this.hide();
  }

  showDateWithTime(jDate, gDate, hour, minute, showGdate = false) {
    const pd = PersianDate.parse(jDate);
    pd.gDate.setHours(hour, minute, 0, 0);

    const timeStr = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;

    let formattedJDate = pd.toString(this.options.formatDate);
    let formattedGDate;

    if (typeof gDate === 'string') {
      const parts = gDate.split('/');
      const gDateObj = new Date(
        parseInt(parts[0]),
        parseInt(parts[1]) - 1,
        parseInt(parts[2]),
        hour,
        minute,
      );
      formattedGDate = formatGregorianDate(gDateObj, this.options.formatDate);
    } else {
      const gDateObj = new Date(gDate);
      gDateObj.setHours(hour, minute, 0, 0);
      formattedGDate = formatGregorianDate(gDateObj, this.options.formatDate);
    }

    const displayValue = showGdate ? formattedGDate : formattedJDate;
    DomUtils.setValue(this.element, displayValue);

    this.element.setAttribute('data-jDate', formattedJDate);
    this.element.setAttribute('data-gDate', formattedGDate);
    this.element.setAttribute('data-time', timeStr);

    this.options.selectedDate = jDate;
    this.options.onSelect();

    this.element.dispatchEvent(
      new CustomEvent('datepicker:select', {
        detail: {
          jalaliDate: formattedJDate,
          gregorianDate: formattedGDate,
          persianDate: pd,
          time: timeStr,
          hour,
          minute,
        },
        bubbles: true,
      }),
    );
  }

  showDate(jDate, gDate, showGdate = false) {
    const pd = PersianDate.parse(jDate);
    const formattedJDate = pd.toString(this.options.formatDate);

    let formattedGDate;
    if (typeof gDate === 'string') {
      const parts = gDate.split('/');
      const gDateObj = new Date(
        parseInt(parts[0]),
        parseInt(parts[1]) - 1,
        parseInt(parts[2]),
      );
      formattedGDate = formatGregorianDate(gDateObj, this.options.formatDate);
    } else {
      formattedGDate = formatGregorianDate(gDate, this.options.formatDate);
    }

    const displayValue = showGdate ? formattedGDate : formattedJDate;
    DomUtils.setValue(this.element, displayValue);

    this.element.setAttribute('data-jDate', formattedJDate);
    this.element.setAttribute('data-gDate', formattedGDate);

    this.options.selectedDate = jDate;
    this.options.onSelect();

    this.element.dispatchEvent(
      new CustomEvent('datepicker:select', {
        detail: {
          jalaliDate: formattedJDate,
          gregorianDate: formattedGDate,
          persianDate: pd,
        },
        bubbles: true,
      }),
    );
  }

  destroy() {
    document.removeEventListener('mouseup', this.#documentClickHandler);
    this.#positionManager.destroy();

    if (this.#blurHandler) {
      DomUtils.off(this.element, 'blur', this.#blurHandler);
    }

    DomUtils.remove(this.calendar);
    DomUtils.removeClass(this.element, 'pdp-el');
    this.element.removeAttribute('pdp-id');
  }

  getDate() {
    return {
      jalali: this.element.getAttribute('data-jDate'),
      gregorian: this.element.getAttribute('data-gDate'),
      persianDate: this.persianDate,
    };
  }

  setDate(dateString) {
    const pd = PersianDate.parse(dateString);
    this.persianDate = pd;
    this.options.selectedDate = dateString;

    const gDate = DateConverter.jalaliToGregorian(pd);
    const gDateStr = `${gDate.getFullYear()}/${gDate.getMonth() + 1}/${gDate.getDate()}`;

    this.showDate(dateString, gDateStr, this.options.showGregorianDate);
    this.#renderer.render();
  }

  refresh() {
    this.#renderer.render();
    this.#positionManager.updatePosition();
  }

  getTimePicker() {
    return this.#timePicker;
  }

  getTime() {
    return {
      hour: this.hour,
      minute: this.minute,
      formatted: `${String(this.hour).padStart(2, '0')}:${String(this.minute).padStart(2, '0')}`,
    };
  }

  setTime(hour, minute) {
    this.hour = hour;
    this.minute = minute;
    if (this.#timePicker) {
      this.#timePicker.setTime(hour, minute);
    }
  }
}
