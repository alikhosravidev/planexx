export const SELECTORS = {
  enhanced: '[data-tom-select]',
  ajax: '[data-tom-select-ajax]',
  tags: '[data-tom-select-tags]',
  multiple: '[data-tom-select-multiple]',
};

export const DEFAULTS = {
  placeholder: 'انتخاب کنید',
  loadThrottle: 300,
  minSearchLength: 1,
  maxItems: null,
  maxOptions: 50,
  closeAfterSelect: true,
  hideSelected: false,
  persist: true,
  createOnBlur: false,
  selectOnTab: true,
};

export const DATA_ATTRIBUTES = {
  url: 'data-url',
  searchFields: 'data-search-fields',
  valueField: 'data-value-field',
  labelField: 'data-label-field',
  dataPath: 'data-path',
  placeholder: 'data-placeholder',
  maxItems: 'data-max-items',
  minSearch: 'data-min-search',
  template: 'data-template',
  defaultValue: 'data-default-value',
  preload: 'data-preload',
  allowCreate: 'data-allow-create',
};
