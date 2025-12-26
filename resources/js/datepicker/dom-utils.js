export const DomUtils = {
  createElement(tag, attributes = {}, children = []) {
    const element = document.createElement(tag);

    Object.entries(attributes).forEach(([key, value]) => {
      if (key === 'className') {
        element.className = value;
      } else if (key === 'style' && typeof value === 'object') {
        Object.assign(element.style, value);
      } else if (key.startsWith('data-')) {
        element.setAttribute(key, value);
      } else if (key === 'html') {
        element.innerHTML = value;
      } else if (key === 'text') {
        element.textContent = value;
      } else {
        element.setAttribute(key, value);
      }
    });

    children.forEach((child) => {
      if (typeof child === 'string') {
        element.appendChild(document.createTextNode(child));
      } else if (child instanceof Node) {
        element.appendChild(child);
      }
    });

    return element;
  },

  on(element, event, handler, options = {}) {
    element.addEventListener(event, handler, options);
    return () => element.removeEventListener(event, handler, options);
  },

  off(element, event, handler) {
    element.removeEventListener(event, handler);
  },

  addClass(element, ...classNames) {
    element.classList.add(...classNames);
  },

  removeClass(element, ...classNames) {
    element.classList.remove(...classNames);
  },

  hasClass(element, className) {
    return element.classList.contains(className);
  },

  toggleClass(element, className, force) {
    return element.classList.toggle(className, force);
  },

  show(element) {
    element.style.display = 'block';
  },

  hide(element) {
    element.style.display = 'none';
  },

  isVisible(element) {
    return element.offsetParent !== null && element.style.display !== 'none';
  },

  getOffset(element) {
    const rect = element.getBoundingClientRect();
    return {
      top: rect.top + window.scrollY,
      left: rect.left + window.scrollX,
    };
  },

  getOuterHeight(element) {
    return element.offsetHeight;
  },

  getOuterWidth(element) {
    return element.offsetWidth;
  },

  empty(element) {
    while (element.firstChild) {
      element.removeChild(element.firstChild);
    }
  },

  append(parent, child) {
    parent.appendChild(child);
  },

  prepend(parent, child) {
    parent.insertBefore(child, parent.firstChild);
  },

  remove(element) {
    if (element.parentNode) {
      element.parentNode.removeChild(element);
    }
  },

  closest(element, selector) {
    return element.closest(selector);
  },

  find(element, selector) {
    return element.querySelector(selector);
  },

  findAll(element, selector) {
    return element.querySelectorAll(selector);
  },

  isInput(element) {
    return element.tagName.toLowerCase() === 'input';
  },

  getValue(element) {
    return DomUtils.isInput(element) ? element.value : element.innerHTML;
  },

  setValue(element, value) {
    if (DomUtils.isInput(element)) {
      element.value = value;
    } else {
      element.innerHTML = value;
    }
  },

  setAttr(element, name, value) {
    element.setAttribute(name, value);
  },

  getAttr(element, name) {
    return element.getAttribute(name);
  },

  css(element, styles) {
    Object.assign(element.style, styles);
  },
};
