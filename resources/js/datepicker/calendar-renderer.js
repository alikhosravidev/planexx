import { DomUtils } from './dom-utils.js';
import { PersianDate, DateConverter } from './persian-date.js';

export class CalendarRenderer {
  #datepicker;
  #styles;

  constructor(datepicker) {
    this.#datepicker = datepicker;
    this.#styles = this.#computeStyles();
  }

  #computeStyles() {
    const { fontSize, cellWidth, cellHeight } = this.#datepicker.options;
    const cw = parseInt(cellWidth, 10);
    const ch = parseInt(cellHeight, 10);

    return {
      cell: {
        width: `${cw}px`,
        height: `${ch}px`,
        lineHeight: `${ch}px`,
        fontSize: `${fontSize}px`,
      },
      header: {
        height: `${ch}px`,
        lineHeight: `${ch}px`,
        fontSize: `${fontSize + 4}px`,
      },
      selectUl: {
        marginTop: `${ch}px`,
        height: `${ch * 7 + 20}px`,
        fontSize: `${fontSize - 2}px`,
      },
      selectMonthLi: {
        height: `${(ch * 7 + 7) / 4}px`,
        lineHeight: `${(ch * 7 + 7) / 4}px`,
        width: `${(6.7 * cw) / 3}px`,
      },
      selectYearLi: {
        height: `${(ch * 7 + 10) / 6}px`,
        lineHeight: `${(ch * 7 + 10) / 6}px`,
        width: `${(6.7 * cw - 14) / 3}px`,
      },
      footer: {
        height: `${ch}px`,
        lineHeight: `${ch}px`,
        fontSize: `${fontSize}px`,
      },
    };
  }

  render() {
    const calendar = this.#datepicker.calendar;
    const { options } = this.#datepicker;
    DomUtils.empty(calendar);

    if (!options.timePickerOnly) {
      this.#renderHeader();
      this.#renderDaysOfWeek();
      this.#renderContent();
    }

    if (options.showTimePicker || options.timePickerOnly) {
      this.#renderTimePicker();
    }

    this.#datepicker.options.onRender();
  }

  #renderTimePicker() {
    const { calendar } = this.#datepicker;
    const timePicker = this.#datepicker.getTimePicker();

    if (timePicker) {
      const timePickerEl = timePicker.render();
      DomUtils.append(calendar, timePickerEl);
    }
  }

  #renderHeader() {
    const { options, persianDate, calendar } = this.#datepicker;

    const monthYearContainer = DomUtils.createElement('div', { className: '' });
    DomUtils.append(calendar, monthYearContainer);

    const header = DomUtils.createElement('div', {
      className: 'pdp-header',
      style: this.#styles.header,
    });
    DomUtils.append(calendar, header);

    const nextArrow = this.#createNextArrow();
    DomUtils.append(header, nextArrow);

    const { monthSelect, yearSelect } = this.#createSelects();
    DomUtils.append(monthYearContainer, monthSelect);
    DomUtils.append(monthYearContainer, yearSelect);

    const titleYearMonth = this.#createMonthYearTitle(monthSelect, yearSelect);
    DomUtils.append(header, titleYearMonth);

    const prevArrow = this.#createPrevArrow();
    DomUtils.append(header, prevArrow);
  }

  #createNextArrow() {
    const { options, persianDate } = this.#datepicker;

    const nextArrow = DomUtils.createElement('div', {
      className: 'nextArrow',
      html: options.nextArrow,
      title: 'ماه بعد',
    });

    const canGoNext =
      options.endDate === null ||
      PersianDate.parse(options.endDate).year > persianDate.year ||
      PersianDate.parse(options.endDate).month > persianDate.month;

    if (canGoNext) {
      DomUtils.on(nextArrow, 'click', () => {
        let nextMonth = persianDate.month + 1;
        while (
          options.selectableMonths.indexOf(nextMonth) === -1 &&
          nextMonth < 13
        ) {
          nextMonth++;
        }
        persianDate.addMonth(nextMonth - persianDate.month);
        this.render();
      });
      DomUtils.removeClass(nextArrow, 'disabled');
    } else {
      DomUtils.addClass(nextArrow, 'disabled');
    }

    return nextArrow;
  }

  #createPrevArrow() {
    const { options, persianDate } = this.#datepicker;

    const prevArrow = DomUtils.createElement('div', {
      className: 'prevArrow',
      html: options.prevArrow,
      title: 'ماه قبل',
    });

    const canGoPrev =
      options.startDate === null ||
      PersianDate.parse(options.startDate).year < persianDate.year ||
      PersianDate.parse(options.startDate).month < persianDate.month;

    if (canGoPrev) {
      DomUtils.on(prevArrow, 'click', () => {
        persianDate.addMonth(-1);
        this.render();
      });
      DomUtils.removeClass(prevArrow, 'disabled');
    } else {
      DomUtils.addClass(prevArrow, 'disabled');
    }

    return prevArrow;
  }

  #createSelects() {
    const monthSelect = DomUtils.createElement('ul', {
      className: 'monthSelect',
      style: { ...this.#styles.selectUl, display: 'none' },
    });

    const yearSelect = DomUtils.createElement('ul', {
      className: 'yearSelect',
      style: { ...this.#styles.selectUl, display: 'none' },
    });

    this.#populateMonthSelect(monthSelect);
    this.#populateYearSelect(yearSelect);

    return { monthSelect, yearSelect };
  }

  #populateMonthSelect(monthSelect) {
    const { options, persianDate } = this.#datepicker;

    const startDate = options.startDate
      ? PersianDate.parse(options.startDate)
      : PersianDate.parse('1/1/1');
    const endDate = options.endDate
      ? PersianDate.parse(options.endDate)
      : PersianDate.parse('9999/1/1');

    for (let i = 1; i <= 12; i++) {
      const monthName = options.months[i - 1];
      const isDisabled =
        options.selectableMonths.indexOf(i) === -1 ||
        (startDate.year === persianDate.year && startDate.month > i) ||
        (endDate.year === persianDate.year && i > endDate.month);

      const li = DomUtils.createElement('li', {
        className: isDisabled ? 'disableMonth' : '',
        html: monthName,
        style: this.#styles.selectMonthLi,
      });

      if (i === persianDate.month) {
        DomUtils.addClass(li, 'selected');
      }

      if (!isDisabled) {
        DomUtils.on(li, 'click', () => {
          persianDate.date = 1;
          persianDate.month = i;
          this.render();
        });
      }

      DomUtils.append(monthSelect, li);
    }
  }

  #populateYearSelect(yearSelect) {
    const { options, persianDate } = this.#datepicker;

    const getSelectableYears = (first, last) => {
      let prepend = false;
      let begin, end;

      if (first === undefined && last === undefined) {
        begin = persianDate.year - 7;
        end = persianDate.year + 14;
      } else if (last === 0) {
        begin = first - 6;
        end = first;
        prepend = true;
      } else if (first === 0) {
        begin = last + 1;
        end = begin + 6;
      }

      const years = [];
      for (let i = begin; i < end && begin > 0; i++) {
        years.push(parseInt(i, 10));
      }

      const yearsToRender =
        options.selectableYears || (prepend ? years.reverse() : years);

      yearsToRender.forEach((year) => {
        const li = DomUtils.createElement('li', {
          html: options.persianNumbers
            ? DateConverter.toPersianDigits(year)
            : year,
          style: this.#styles.selectYearLi,
        });

        if (year === persianDate.year) {
          DomUtils.addClass(li, 'selected');
        }

        li.setAttribute('value', year);

        DomUtils.on(li, 'click', () => {
          const startDate = options.startDate
            ? PersianDate.parse(options.startDate)
            : PersianDate.parse('1/1/1');
          const endDate = options.endDate
            ? PersianDate.parse(options.endDate)
            : PersianDate.parse('9999/1/1');

          persianDate.date = 1;
          persianDate.year = parseInt(year, 10);

          if (endDate.year === year || endDate.year === 9999) {
            persianDate.month = endDate.month;
          }
          if (startDate.year === year || startDate.year === 9999) {
            persianDate.month = startDate.month;
          }

          this.render();
        });

        if (prepend) {
          DomUtils.prepend(yearSelect, li);
        } else {
          DomUtils.append(yearSelect, li);
        }
      });
    };

    getSelectableYears();

    if (!options.selectableYears) {
      DomUtils.on(yearSelect, 'scroll', () => {
        const items = yearSelect.querySelectorAll('li');
        const firstYear = parseInt(items[0]?.getAttribute('value') || 0, 10);
        const lastYear = parseInt(
          items[items.length - 1]?.getAttribute('value') || 0,
          10,
        );
        const lisHeight =
          (items.length / 3) * (items[0]?.offsetHeight + 4 || 0);
        const scrollTop = yearSelect.scrollTop;
        const scrollLen = scrollTop.toString().length;
        const com = scrollLen * 500;

        if (scrollTop < scrollLen * 100 && firstYear >= 1) {
          getSelectableYears(firstYear, 0);
        }

        const com2 = scrollLen * 100;
        if (lisHeight - scrollTop > -com2 && lisHeight - scrollTop < com2) {
          getSelectableYears(0, lastYear);
          yearSelect.scrollTop = scrollTop - 50;
        }

        if (scrollTop < scrollLen && firstYear >= 30) {
          yearSelect.scrollTop = scrollLen * 100;
        }
      });
    }
  }

  #createMonthYearTitle(monthSelect, yearSelect) {
    const { options, persianDate } = this.#datepicker;

    const monthText = DomUtils.createElement('span', {
      html: options.months[persianDate.month - 1],
    });

    DomUtils.on(monthText, 'mousedown', (e) => e.preventDefault());
    DomUtils.on(monthText, 'click', (e) => {
      e.stopPropagation();
      yearSelect.style.display = 'none';
      monthSelect.style.display = 'inline-block';
    });

    const yearText = DomUtils.createElement('span', {
      html: options.persianNumbers
        ? DateConverter.toPersianDigits(persianDate.year)
        : persianDate.year,
    });

    DomUtils.on(yearText, 'mousedown', (e) => e.preventDefault());
    DomUtils.on(yearText, 'click', (e) => {
      e.stopPropagation();
      monthSelect.style.display = 'none';
      yearSelect.style.display = 'inline-block';
      yearSelect.scrollTop = 70;
    });

    const spacer = DomUtils.createElement('span', { html: '&nbsp;&nbsp;' });

    const titleYearMonth = DomUtils.createElement('div', {
      className: 'monthYear',
    });
    DomUtils.append(titleYearMonth, monthText);
    DomUtils.append(titleYearMonth, spacer);
    DomUtils.append(titleYearMonth, yearText);

    return titleYearMonth;
  }

  #renderDaysOfWeek() {
    const { options, calendar } = this.#datepicker;

    const row = DomUtils.createElement('div', { className: 'dows' });

    for (let i = 0; i < 7; i++) {
      const cell = DomUtils.createElement('div', {
        className: 'dow cell',
        html: options.shortDowTitle[i],
        style: this.#styles.cell,
      });
      DomUtils.append(row, cell);
    }

    DomUtils.append(calendar, row);
  }

  #renderContent() {
    const { options, persianDate, calendar } = this.#datepicker;

    const daysContainer = DomUtils.createElement('div', { className: 'days' });
    DomUtils.append(calendar, daysContainer);

    persianDate.date = 1;
    const startDay = DateConverter.getWeekday(persianDate);
    const endDay = DateConverter.getLastDayOfMonth(persianDate);

    for (let row = 0, cellIndex = 0; row < 6; row++) {
      const rowEl = DomUtils.createElement('div');

      for (let col = 0; col < 7; col++, cellIndex++) {
        let cell;

        if (cellIndex < startDay || cellIndex - startDay + 1 > endDay) {
          cell = DomUtils.createElement('div', {
            className: 'nul cell',
            html: '&nbsp;',
            style: this.#styles.cell,
          });
        } else {
          cell = this.#createDayCell(cellIndex, startDay);
        }

        DomUtils.append(rowEl, cell);
      }

      DomUtils.append(daysContainer, rowEl);
    }
  }

  #createDayCell(cellIndex, startDay) {
    const { options, persianDate } = this.#datepicker;
    const dayNumber = cellIndex - startDay + 1;
    const col = cellIndex % 7;

    const dt = persianDate.clone();
    dt.date = dayNumber;
    dt.day = DateConverter.getWeekday(dt);

    const now = PersianDate.now();
    const classes = ['day', 'cell'];

    if (now.compare(dt) === 0) classes.push('today');
    if (col === 6) classes.push('friday');

    if (options.startDate !== null) {
      const startPd = PersianDate.parse(options.startDate);
      const endPd = PersianDate.parse(options.endDate);
      if (startPd.compare(dt) === -1 || endPd.compare(dt) === 1) {
        classes.push('disday');
      }
    }

    if (options.selectedDate !== null) {
      const selectedPd = PersianDate.parse(options.selectedDate);
      if (
        selectedPd.date === dayNumber &&
        selectedPd.month === persianDate.month &&
        selectedPd.year === persianDate.year
      ) {
        classes.push('selday');
      }
    } else if (
      dayNumber === now.date &&
      persianDate.month === now.month &&
      persianDate.year === now.year
    ) {
      classes.push('selday');
    }

    const cell = DomUtils.createElement('div', {
      className: classes.join(' '),
      html: options.persianNumbers
        ? DateConverter.toPersianDigits(dayNumber)
        : dayNumber,
      style: this.#styles.cell,
    });

    const jDate = dt.toString('YYYY/MM/DD');
    const gDate = DateConverter.jalaliToGregorian(dt);
    const gDateStr = `${gDate.getFullYear()}/${gDate.getMonth() + 1}/${gDate.getDate()}`;

    cell.setAttribute('data-jdate', jDate);
    cell.setAttribute('data-gdate', gDateStr);

    const isDisabled = classes.includes('disday');
    if (!isDisabled) {
      DomUtils.on(cell, 'click', () => {
        this.#datepicker.selectDate(jDate, gDateStr);
      });
    }

    return cell;
  }

  #renderFooter() {
    const { options, persianDate, calendar } = this.#datepicker;

    const footer = DomUtils.createElement('div', {
      className: 'pdp-footer',
      style: this.#styles.footer,
    });
    DomUtils.append(calendar, footer);

    if (options.selectableMonths.indexOf(persianDate.month) > -1) {
      const now = PersianDate.now();
      const goToday = DomUtils.createElement('a', {
        className: 'goToday',
        href: 'javascript:;',
        html: 'هم اکنون',
      });

      const jDate = now.toString('YYYY/MM/DD/DW');
      const gDate = DateConverter.jalaliToGregorian(now);
      const gDateStr = `${gDate.getFullYear()}/${gDate.getMonth() + 1}/${gDate.getDate()}`;

      goToday.setAttribute('data-jdate', jDate);
      goToday.setAttribute('data-gdate', gDateStr);

      if (options.startDate === null) {
        DomUtils.on(goToday, 'click', () => {
          this.#datepicker.persianDate = PersianDate.now();
          this.#datepicker.showDate(jDate, gDateStr);
          this.render();

          const todayCell = calendar.querySelector('.today');
          if (todayCell) {
            calendar
              .querySelectorAll('.day')
              .forEach((d) => DomUtils.removeClass(d, 'selday'));
            DomUtils.addClass(todayCell, 'selday');
          }

          this.#datepicker.hide();
        });
      }

      DomUtils.append(footer, goToday);
    }
  }
}
