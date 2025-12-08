export function initPersianDigits(options = {}) {
  const {
    skipTags = [
      'SCRIPT',
      'STYLE',
      'CODE',
      'PRE',
      'KBD',
      'SAMP',
      'TEXTAREA',
      'INPUT',
    ],
    skipAttr = 'data-no-digit-localize',
    skipClasses = [],
  } = options;

  const PERSIAN_DIGITS = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
  const DIGIT_REGEX = /\d/;
  const SKIP_TAGS = new Set(skipTags);
  const SKIP_CLASS_SET = new Set(
    skipClasses.map((c) => c.trim()).filter(Boolean),
  );

  function elementHasSkipClass(el) {
    if (!SKIP_CLASS_SET.size) return false;
    if (el.classList) {
      for (const cls of SKIP_CLASS_SET) {
        if (el.classList.contains(cls)) return true;
      }
    }
    return false;
  }

  function shouldSkip(node) {
    if (!node) return true;
    if (node.nodeType === Node.ELEMENT_NODE) {
      const el = node;
      if (SKIP_TAGS.has(el.tagName)) return true;
      if (el.hasAttribute(skipAttr)) return true;
      if (el.isContentEditable) return true;
      if (elementHasSkipClass(el)) return true;
      if (SKIP_CLASS_SET.size && el.closest) {
        for (const cls of SKIP_CLASS_SET) {
          if (el.closest(`.${cls}`)) return true;
        }
      }
    }
    return false;
  }

  function toPersian(text) {
    if (!DIGIT_REGEX.test(text)) return text;
    let out = '';
    for (let i = 0; i < text.length; i++) {
      const ch = text[i];
      if (ch >= '0' && ch <= '9') {
        out += PERSIAN_DIGITS[ch.charCodeAt(0) - 48];
      } else {
        out += ch;
      }
    }
    return out;
  }

  function convertTextNode(node) {
    if (node.nodeType !== Node.TEXT_NODE) return;
    const parent = node.parentNode;
    if (!parent || shouldSkip(parent)) return;
    const orig = node.nodeValue;
    const converted = toPersian(orig);
    if (converted !== orig) {
      node.nodeValue = converted;
    }
  }

  function walkAndConvert(root) {
    if (shouldSkip(root)) return;
    const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
      acceptNode: (node) => {
        const p = node.parentNode;
        if (!p || shouldSkip(p)) return NodeFilter.FILTER_REJECT;
        return DIGIT_REGEX.test(node.nodeValue)
          ? NodeFilter.FILTER_ACCEPT
          : NodeFilter.FILTER_REJECT;
      },
    });
    let node;
    while ((node = walker.nextNode())) {
      convertTextNode(node);
    }
  }

  function observeMutations() {
    const observer = new MutationObserver((mutations) => {
      for (const m of mutations) {
        if (m.type === 'characterData') {
          convertTextNode(m.target);
        } else if (m.type === 'childList') {
          m.addedNodes.forEach((n) => {
            if (n.nodeType === Node.TEXT_NODE) {
              convertTextNode(n);
            } else if (n.nodeType === Node.ELEMENT_NODE) {
              walkAndConvert(n);
            }
          });
        }
      }
    });
    observer.observe(document.documentElement, {
      subtree: true,
      childList: true,
      characterData: true,
    });
  }

  function init() {
    if (!document.body) return;
    walkAndConvert(document.body);
    observeMutations();
  }

  document.addEventListener('DOMContentLoaded', init, { once: true });
}
