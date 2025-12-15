import {
  tomSelectService,
  initTomSelect,
  destroyTomSelect,
  getTomSelectInstance,
} from './service.js';

export {
  tomSelectService,
  initTomSelect,
  destroyTomSelect,
  getTomSelectInstance,
};

document.addEventListener('DOMContentLoaded', () => {
  initTomSelect();
});

document.addEventListener('modal:opened', (e) => {
  const modal = e.detail?.modal || e.target;
  if (modal) {
    initTomSelect(modal);
  }
});

document.addEventListener('content:loaded', (e) => {
  const container = e.detail?.container || document;
  initTomSelect(container);
});
