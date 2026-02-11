import {
  PERSIAN_MONTHS,
  PERSIAN_DAYS_OF_WEEK,
  PERSIAN_DIGITS,
} from './config.js';

export class PersianDate {
  constructor(year = 1300, month = 1, date = 1) {
    this.year = year;
    this.month = month;
    this.date = date;
    this.day = 1;
    this.gDate = new Date();
  }

  static now() {
    return DateConverter.gregorianToJalali(new Date());
  }

  static parse(dateString) {
    if (!dateString || typeof dateString !== 'string') {
      return PersianDate.now();
    }

    // Try to parse Gregorian date first (YYYY-MM-DD or YYYY-MM-DD HH:MM:SS format)
    if (dateString.includes('-')) {
      const dateObj = new Date(dateString);
      if (!isNaN(dateObj.getTime())) {
        return DateConverter.gregorianToJalali(dateObj);
      }
    }

    // Parse Persian date (YYYY/MM/DD format)
    const parts = dateString.split('/');
    if (parts.length < 3) {
      return PersianDate.now();
    }

    const year = parseInt(parts[0], 10);
    const month = parseInt(parts[1], 10);
    const date = parseInt(parts[2], 10);

    const pd = new PersianDate(year, month, date);
    pd.day = DateConverter.getWeekday(pd);
    pd.gDate = DateConverter.jalaliToGregorian(pd);

    return pd;
  }

  clone() {
    const cloned = new PersianDate(this.year, this.month, this.date);
    cloned.day = this.day;
    cloned.gDate = new Date(this.gDate);
    return cloned;
  }

  addDay(days) {
    const direction = days > 0 ? 1 : -1;
    const iterations = Math.abs(days);

    for (let i = 0; i < iterations; i++) {
      const prevMonth = this.clone();
      prevMonth.addMonth(-1);
      const lastDayOfPrevMonth = DateConverter.getLastDayOfMonth(prevMonth);
      const lastDayOfCurrentMonth = DateConverter.getLastDayOfMonth(this);

      this.date += direction;

      if (direction > 0) {
        if (this.date > lastDayOfCurrentMonth) {
          this.date = 1;
          this.addMonth(1);
        }
      } else {
        if (this.date === 0) {
          this.addMonth(-1);
          this.date = lastDayOfPrevMonth;
        }
      }
    }

    return this;
  }

  addMonth(months) {
    const direction = months > 0 ? 1 : -1;
    const iterations = Math.abs(months);

    for (let i = 0; i < iterations; i++) {
      this.month += direction;

      if (this.month === 13) {
        this.month = 1;
        this.addYear(1);
      } else if (this.month === 0) {
        this.month = 12;
        this.addYear(-1);
      }
    }

    return this;
  }

  addYear(years) {
    this.year += years;
    return this;
  }

  compare(other) {
    if (
      other.year === this.year &&
      other.month === this.month &&
      other.date === this.date
    ) {
      return 0;
    }
    if (other.year > this.year) return 1;
    if (other.year === this.year && other.month > this.month) return 1;
    if (
      other.year === this.year &&
      other.month === this.month &&
      other.date > this.date
    )
      return 1;
    return -1;
  }

  toString(formatDate) {
    if (!formatDate) {
      return `${this.year}/${this.month}/${this.date}`;
    }

    const hours = this.gDate.getHours();
    const minutes = this.gDate.getMinutes();
    const seconds = this.gDate.getSeconds();

    return formatDate
      .replace('YYYY', this.year)
      .replace('MM', this.month)
      .replace('DD', this.date)
      .replace('0M', this.month > 9 ? this.month : `0${this.month}`)
      .replace('0D', this.date > 9 ? this.date : `0${this.date}`)
      .replace('hh', hours)
      .replace('mm', minutes)
      .replace('ss', seconds)
      .replace('0h', hours > 9 ? hours : `0${hours}`)
      .replace('0m', minutes > 9 ? minutes : `0${minutes}`)
      .replace('0s', seconds > 9 ? seconds : `0${seconds}`)
      .replace('tm', hours >= 12 && minutes > 0 ? 'ب.ظ' : 'ق.ظ')
      .replace('ms', this.gDate.getMilliseconds())
      .replace('NM', PERSIAN_MONTHS[this.month - 1])
      .replace('DW', this.day)
      .replace('ND', PERSIAN_DAYS_OF_WEEK[this.day]);
  }
}

export class DateConverter {
  static toPersianDigits(value) {
    const str = String(value);
    let result = '';

    for (let i = 0; i < str.length; i++) {
      const digit = parseInt(str[i], 10);
      result += isNaN(digit) ? str[i] : PERSIAN_DIGITS[digit];
    }

    return result;
  }

  static gregorianToJalali(date) {
    const gy = date.getFullYear();
    const gm = date.getMonth() + 1;
    const gd = date.getDate();

    const gDaysInMonth = [
      0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334,
    ];

    let jy = gy > 1600 ? 979 : 0;
    const gyOffset = gy > 1600 ? gy - 1600 : gy - 621;

    const gy2 = gm > 2 ? gyOffset + 1 : gyOffset;
    let days =
      365 * gyOffset +
      Math.floor((gy2 + 3) / 4) -
      Math.floor((gy2 + 99) / 100) +
      Math.floor((gy2 + 399) / 400) -
      80 +
      gd +
      gDaysInMonth[gm - 1];

    jy += 33 * Math.floor(days / 12053);
    days %= 12053;

    jy += 4 * Math.floor(days / 1461);
    days %= 1461;

    if (days > 365) {
      jy += Math.floor((days - 1) / 365);
      days = (days - 1) % 365;
    }

    const jm =
      days < 186
        ? 1 + Math.floor(days / 31)
        : 7 + Math.floor((days - 186) / 30);
    const jd = 1 + (days < 186 ? days % 31 : (days - 186) % 30);

    const pd = new PersianDate(jy, jm, jd);
    pd.gDate = new Date(date);

    return pd;
  }

  static jalaliToGregorian(pd) {
    let jy = pd.year;
    const jm = pd.month;
    const jd = pd.date;

    let gy = jy > 979 ? 1600 : 621;
    jy = jy > 979 ? jy - 979 : jy;

    let days =
      365 * jy +
      Math.floor(jy / 33) * 8 +
      Math.floor(((jy % 33) + 3) / 4) +
      78 +
      jd +
      (jm < 7 ? (jm - 1) * 31 : (jm - 7) * 30 + 186);

    gy += 400 * Math.floor(days / 146097);
    days %= 146097;

    if (days > 36524) {
      gy += 100 * Math.floor(--days / 36524);
      days %= 36524;
      if (days >= 365) days++;
    }

    gy += 4 * Math.floor(days / 1461);
    days %= 1461;

    if (days > 365) {
      gy += Math.floor((days - 1) / 365);
      days = (days - 1) % 365;
    }

    let gd = days + 1;
    const isLeap = (gy % 4 === 0 && gy % 100 !== 0) || gy % 400 === 0;
    const salA = [
      0,
      31,
      isLeap ? 29 : 28,
      31,
      30,
      31,
      30,
      31,
      31,
      30,
      31,
      30,
      31,
    ];

    let gm;
    for (gm = 0; gm < 13; gm++) {
      const v = salA[gm];
      if (gd <= v) break;
      gd -= v;
    }

    const now = new Date();
    return new Date(
      gy,
      gm - 1,
      gd,
      now.getHours(),
      now.getMinutes(),
      now.getSeconds(),
      now.getMilliseconds(),
    );
  }

  static getWeekday(pd) {
    const gds = [1, 2, 3, 4, 5, 6, 0];
    return gds[DateConverter.jalaliToGregorian(pd).getDay()];
  }

  static getLastDayOfMonth(pd) {
    const { year, month } = pd;

    if (month >= 1 && month <= 6) return 31;
    if (month >= 7 && month < 12) return 30;
    return DateConverter.isLeapYear(year) ? 30 : 29;
  }

  static isLeapYear(year) {
    const leapYears =
      year > 1342
        ? [1, 5, 9, 13, 17, 22, 26, 30]
        : [1, 5, 9, 13, 17, 21, 26, 30];
    return leapYears.includes(year % 33);
  }
}

export function formatGregorianDate(date, formatDate) {
  if (!formatDate || formatDate === 'default') {
    return date.toLocaleDateString();
  }

  const months = [
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
  const dows = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

  return formatDate
    .replace('YYYY', date.getFullYear())
    .replace('MM', date.getMonth() + 1)
    .replace('DD', date.getDate())
    .replace(
      '0M',
      date.getMonth() + 1 > 9 ? date.getMonth() + 1 : `0${date.getMonth() + 1}`,
    )
    .replace('0D', date.getDate() > 9 ? date.getDate() : `0${date.getDate()}`)
    .replace('hh', date.getHours() || new Date().getHours())
    .replace('mm', date.getMinutes() || new Date().getMinutes())
    .replace('ss', date.getSeconds() || new Date().getSeconds())
    .replace(
      '0h',
      date.getHours() > 9 ? date.getHours() : `0${date.getHours()}`,
    )
    .replace(
      '0m',
      date.getMinutes() > 9 ? date.getMinutes() : `0${date.getMinutes()}`,
    )
    .replace(
      '0s',
      date.getSeconds() > 9 ? date.getSeconds() : `0${date.getSeconds()}`,
    )
    .replace('ms', date.getMilliseconds() || new Date().getMilliseconds())
    .replace('tm', date.getHours() >= 12 && date.getMinutes() > 0 ? 'PM' : 'AM')
    .replace('NM', months[date.getMonth()])
    .replace('DW', date.getDay())
    .replace('ND', dows[date.getDay()]);
}
