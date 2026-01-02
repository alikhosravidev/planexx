class Tooltipper {
  constructor() {
    this.clearTooltips = () => {
      for (const uid in this.trackedElements) {
        const tooltip = document.body.querySelector(`tool-tip[uid="${uid}"]`);
        if (tooltip && tooltip.isConnected) {
          tooltip.remove();
        }

        // Restore original title for the element
        const el = document.body.querySelector(`[data-tooltip-uid="${uid}"]`);
        if (el && el.hasAttribute('data-original-title')) {
          el.setAttribute('title', el.getAttribute('data-original-title'));
          el.removeAttribute('data-original-title');
        }
      }
    };
    this.clickTooltip = (e) => {
      const el = e.target?.closest('[data-tooltip],[title]');
      if (!(el instanceof HTMLElement) || !this.hasTooltipAttribute(el)) {
        return;
      }
      if (e instanceof KeyboardEvent) {
        if (e.key !== ' ') {
          return;
        }
      }
      if (!el.dataset.tooltipUid) {
        el.dataset.tooltipUid = this.uuid();
      }
      const tooltip = document.body.querySelector(
        `tool-tip[uid="${el.dataset.tooltipUid}"]`,
      );
      if (tooltip) {
        tooltip?.remove();
      }
    };
    this.showTooltip = (e) => {
      const el = e.target;
      if (
        !(el instanceof HTMLElement) ||
        !this.hasTooltipAttribute(el) ||
        (typeof TouchEvent !== 'undefined' && e instanceof TouchEvent) ||
        (e instanceof FocusEvent && this.deviceType !== 1)
      ) {
        return;
      }
      let text = this.getTitle(el);
      if (!text || !text.length) {
        console.warn(
          `Tooltip could not be created -- missing aria-label, tooltip, or title attribute.`,
        );
        return;
      }
      if (!el.dataset.tooltipUid) {
        el.dataset.tooltipUid = this.uuid();
      }

      // Store original title and remove it to prevent browser's default tooltip
      if (el.hasAttribute('title') && !el.hasAttribute('data-original-title')) {
        el.setAttribute('data-original-title', el.getAttribute('title'));
        el.removeAttribute('title');
      }

      // Check if tooltip already exists for this element
      let tooltip = document.body.querySelector(
        `tool-tip[uid="${el.dataset.tooltipUid}"]`,
      );

      if (!tooltip) {
        tooltip = document.createElement('tool-tip');
        tooltip.setAttribute('uid', el.dataset.tooltipUid);
        tooltip.setAttribute('role', 'tooltip');
        tooltip.style.position = 'absolute';
        tooltip.style.zIndex = '99999999';
        document.body.appendChild(tooltip);
      }

      // Use textContent instead of innerHTML for security
      tooltip.textContent = text;
      tooltip.style.opacity = '0';

      this.placeTooltip(el, tooltip);
      tooltip.classList.add('visible');
      tooltip.style.opacity = '1';

      if (!(el.dataset.tooltipUid in this.trackedElements)) {
        this.trackedElements[el.dataset.tooltipUid] = null;
      }
    };
    this.hideTooltip = (e) => {
      const el = e.target;

      if (
        !(el instanceof HTMLElement) ||
        !this.hasTooltipAttribute(el) ||
        !el?.dataset?.tooltipUid
      ) {
        return;
      }
      const tooltip = document.body.querySelector(
        `tool-tip[uid="${el.dataset.tooltipUid}"]`,
      );
      if (tooltip && tooltip.isConnected) {
        tooltip.remove();
      }

      // Restore original title attribute if it was removed
      if (el.hasAttribute('data-original-title')) {
        el.setAttribute('title', el.getAttribute('data-original-title'));
        el.removeAttribute('data-original-title');
      }
    };
    this.hasTooltipAttribute = (el) => {
      return (
        el.hasAttribute('data-tooltip') ||
        el.hasAttribute('title') ||
        el.hasAttribute('data-original-title') ||
        el.hasAttribute('aria-label')
      );
    };
    this.getTitle = (el) => {
      let text = el.getAttribute('data-tooltip');
      if (!text || !text.length) {
        text = el.getAttribute('aria-label');
      }
      if (!text || !text.length) {
        text = el.getAttribute('data-original-title');
      }
      if (!text || !text.length) {
        text = el.getAttribute('title');
      }

      return text;
    };
    this.uuid = () => {
      return (Math.random() + 1).toString(36).substring(5);
    };
    this.trackedElements = {};
    this.deviceType = 1;
    document.addEventListener('mouseenter', this.showTooltip, {
      capture: true,
      passive: true,
    });
    document.addEventListener('focus', this.showTooltip, {
      capture: true,
      passive: true,
    });
    document.addEventListener('mouseleave', this.hideTooltip, {
      capture: true,
      passive: true,
    });
    document.addEventListener('blur', this.hideTooltip, {
      capture: true,
      passive: true,
    });
    document.addEventListener('click', this.clickTooltip, {
      capture: true,
      passive: true,
    });
    document.addEventListener('keypress', this.clickTooltip, {
      capture: true,
      passive: true,
    });
    document.addEventListener(
      'touchstart',
      () => {
        this.deviceType = 2;
      },
      { capture: true, passive: true },
    );
    document.addEventListener(
      'mousemove',
      () => {
        this.deviceType = 1;
      },
      { capture: true, passive: true },
    );
    window.addEventListener('scroll', this.clearTooltips, {
      capture: true,
      passive: true,
    });
    window.addEventListener('resize', this.clearTooltips, {
      capture: true,
      passive: true,
    });
    this.tick();
  }

  tick() {
    for (const uid in this.trackedElements) {
      const el = document.body.querySelector(`[data-tooltip-uid="${uid}"]`);
      const tooltip = document.body.querySelector(`tool-tip[uid="${uid}"]`);
      if (el == null) {
        delete this.trackedElements[uid];
        if (tooltip && tooltip.isConnected) {
          tooltip.remove();
        }
      } else if (tooltip != null) {
        let text = this.getTitle(el);
        if (text !== tooltip.textContent) {
          tooltip.textContent = text;
          this.placeTooltip(el, tooltip);
        }
      }
    }
    window.requestAnimationFrame(this.tick.bind(this));
  }

  placeTooltip(el, tooltip) {
    const elBounds = el.getBoundingClientRect();
    const tipBounds = tooltip.getBoundingClientRect();
    let tooltipLeft = elBounds.left + elBounds.width / 2 - tipBounds.width / 2;
    if (tooltipLeft + tipBounds.width > window.innerWidth - 4) {
      const diff = tooltipLeft + tipBounds.width - window.innerWidth + 4;
      tooltipLeft -= diff;
    } else if (tooltipLeft < 4) {
      tooltipLeft = 4;
    }
    let tooltipTop = elBounds.top + window.scrollY - (tipBounds.height + 3);

    tooltip.style.top = `${tooltipTop}px`;
    tooltip.style.left = `${tooltipLeft}px`;
  }
}

export const tooltipper = new Tooltipper();
