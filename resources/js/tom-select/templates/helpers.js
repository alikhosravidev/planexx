import { getDataAttr, safeGetByPath } from '../utils';

export function createAvatar(url, className = 'avatar') {
  return url ? `<img class="${className}" src="${url}" />` : '';
}

export function fallback(value, defaultText = 'ندارد') {
  return value || defaultText;
}

export const baseTemplate = {
  selection: undefined,
  template: undefined,
  getResults: () => ({ results: [] }),
  setRecentSelected: () => {},
};

export function getDisableConfig(el) {
  return {
    disableCondition: getDataAttr(el, 'disable-condition') || getDataAttr(el, 'disable_condition'),
    disableReason: getDataAttr(el, 'disable-reason') || getDataAttr(el, 'disable_reason'),
  };
}

export function toggleCategoryButtons(showCreate) {
  const startButton = document.getElementById('start');
  const createCategory = document.getElementById('create-category');

  if (createCategory) createCategory.style.display = showCreate ? 'inline-flex' : 'none';
  if (startButton) startButton.style.display = showCreate ? 'none' : 'inline-flex';
}

export { getDataAttr, safeGetByPath };
