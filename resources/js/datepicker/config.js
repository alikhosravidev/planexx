export const PERSIAN_MONTHS = [
  'فروردین',
  'اردیبهشت',
  'خرداد',
  'تیر',
  'مرداد',
  'شهریور',
  'مهر',
  'آبان',
  'آذر',
  'دی',
  'بهمن',
  'اسفند',
];

export const PERSIAN_DAYS_OF_WEEK = [
  'شنبه',
  'یکشنبه',
  'دوشنبه',
  'سه شنبه',
  'چهارشنبه',
  'پنج شنبه',
  'جمعه',
];

export const PERSIAN_SHORT_DAYS = ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'];

export const PERSIAN_DIGITS = [
  '۰',
  '۱',
  '۲',
  '۳',
  '۴',
  '۵',
  '۶',
  '۷',
  '۸',
  '۹',
];

export const GREGORIAN_MONTHS = [
  'January',
  'February',
  'March',
  'April',
  'May',
  'June',
  'July',
  'August',
  'September',
  'October',
  'November',
  'December',
];

export const GREGORIAN_DAYS = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

export const DEFAULT_OPTIONS = {
  months: PERSIAN_MONTHS,
  dowTitle: PERSIAN_DAYS_OF_WEEK,
  shortDowTitle: PERSIAN_SHORT_DAYS,
  showGregorianDate: false,
  persianNumbers: true,
  formatDate: 'YYYY/MM/DD',
  selectedBefore: false,
  selectedDate: null,
  startDate: null,
  endDate: null,
  prevArrow: '◄',
  nextArrow: '►',
  theme: 'default',
  alwaysShow: false,
  selectableYears: null,
  selectableMonths: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
  cellWidth: 25,
  cellHeight: 20,
  fontSize: 13,
  isRTL: false,
  closeOnBlur: true,
  calendarPosition: { x: 0, y: 0 },
  onShow: () => {},
  onHide: () => {},
  onSelect: () => {},
  onRender: () => {},

  showTimePicker: false,
  timePickerOnly: false,
  defaultHour: null,
  defaultMinute: null,
  hourStep: 1,
  minuteStep: 5,
  minTime: null,
  maxTime: null,
  use24Hour: true,
  timeLabels: {
    hour: 'ساعت',
    minute: 'دقیقه',
    confirm: 'تایید',
    now: 'اکنون',
  },
};

export const SELECTORS = {
  datepicker: '[data-datepicker]',
  datepickerFa: '[data-datepicker-fa]',
  datepickerStart: '[data-datepicker-start]',
  datepickerEnd: '[data-datepicker-end]',
  datepickerMiddle: '[data-datepicker-middle]',
  datetimepicker: '[data-datetimepicker]',
  datetimepickerFa: '[data-datetimepicker-fa]',
  timepicker: '[data-timepicker]',
};

export const DATA_ATTRIBUTES = {
  format: 'data-datepicker-format',
  gregorian: 'data-datepicker-gregorian',
  minDate: 'data-datepicker-min',
  maxDate: 'data-datepicker-max',
  defaultValue: 'data-datepicker-value',
  showTime: 'data-datepicker-time',
  timeOnly: 'data-datepicker-time-only',
  hourStep: 'data-datepicker-hour-step',
  minuteStep: 'data-datepicker-minute-step',
  minTime: 'data-datepicker-min-time',
  maxTime: 'data-datepicker-max-time',
};
