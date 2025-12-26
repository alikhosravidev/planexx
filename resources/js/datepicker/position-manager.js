import { DomUtils } from './dom-utils.js';

export class PositionManager {
  #datepicker;
  #resizeHandler;

  constructor(datepicker) {
    this.#datepicker = datepicker;
    this.#resizeHandler = this.updatePosition.bind(this);
  }

  init() {
    window.addEventListener('resize', this.#resizeHandler);
    this.updatePosition();
  }

  destroy() {
    window.removeEventListener('resize', this.#resizeHandler);
  }

  updatePosition() {
    const { element, calendar, options } = this.#datepicker;

    const elPos = DomUtils.getOffset(element);
    const inputH = DomUtils.getOuterHeight(element);

    const wasHidden = !DomUtils.isVisible(calendar) && !options.alwaysShow;
    let prevVisibility;

    if (wasHidden) {
      prevVisibility = calendar.style.visibility;
      calendar.style.visibility = 'hidden';
      calendar.style.display = 'block';
    }

    const calH = DomUtils.getOuterHeight(calendar);

    if (wasHidden) {
      calendar.style.display = 'none';
      calendar.style.visibility = prevVisibility || '';
    }

    const scrollTop = window.scrollY;
    const viewportH = window.innerHeight;
    const spaceAbove = elPos.top - scrollTop;
    const spaceBelow = scrollTop + viewportH - (elPos.top + inputH);

    let top;
    if (spaceBelow < calH && spaceAbove > calH) {
      top = elPos.top - calH + options.calendarPosition.y;
    } else {
      top = elPos.top + inputH + options.calendarPosition.y;
    }

    DomUtils.css(calendar, {
      top: `${top}px`,
      left: `${elPos.left + options.calendarPosition.x}px`,
    });
  }
}
