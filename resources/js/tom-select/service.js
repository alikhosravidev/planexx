import TomSelect from 'tom-select';

import templates from './templates/index.js';
import { tomSelectApi } from './api';
import { SELECT2_ELEMENTS, DEFAULTS } from './config';
import { buildSearchParams, normalizeData, buildUrl } from './utils';

class TomSelectService {
  /**
   * Create new TomSelect instance
   */
  create(selector, templateName = 'default') {
    const elements = document.querySelectorAll(selector);
    elements.forEach((el) => this.#initElement(el, templateName));
  }

  /**
   * Initialize a single element
   */
  #initElement(el, templateName) {
    const config = this.#extractConfig(el, templateName);
    const instance = this.#createInstance(el, config);

    this.#setupEvents(el, instance);
    this.#loadDefaultValue(el, instance, config);
  }

  /**
   * Extract configuration from element
   */
  #extractConfig(el, templateName) {
    const currentTemplate = el.getAttribute('data-template');
    const template =
      templates[currentTemplate || templateName] || templates.default;

    return {
      template,
      ajaxUrl: el.getAttribute('data-ajax_url'),
      placeholder: el.getAttribute('data-placeholder') || DEFAULTS.placeholder,
      searchableFields: el.getAttribute('data-searchable-fields'),
      tags: el.getAttribute('data-tags') === 'true',
      dataPath: el.getAttribute('data_path'),
      defaultValue: el.getAttribute('data-recent-selected'),
    };
  }

  /**
   * Create TomSelect instance
   */
  #createInstance(el, config) {
    const { template, ajaxUrl, placeholder, tags, searchableFields, dataPath } =
      config;

    const tomSelectConfig = {
      placeholder,
      create: tags,
      loadThrottle: DEFAULTS.loadThrottle,
      controlInput: null,
      hidePlaceholder: false,
      load: ajaxUrl
        ? (query, callback) => {
            this.#loadOptions(query, callback, {
              ajaxUrl,
              searchableFields,
              dataPath,
              template,
              el,
            });
          }
        : undefined,
    };

    if (template.template || template.selection) {
      tomSelectConfig.render = {};
      if (template.template) {
        tomSelectConfig.render.option = (data) =>
          this.#toNode(template.template(data));
      }
      if (template.selection) {
        tomSelectConfig.render.item = (data) =>
          this.#toNode(template.selection(data));
      }
    }

    return new TomSelect(el, tomSelectConfig);
  }

  /**
   * Convert HTML string or HTMLElement into HTMLElement
   */
  #toNode(content) {
    if (!content) return undefined;
    if (content instanceof HTMLElement) return content;
    const wrapper = document.createElement('div');
    wrapper.innerHTML = String(content).trim();
    return wrapper.firstElementChild || wrapper;
  }

  /**
   * Load options from API
   */
  async #loadOptions(
    query,
    callback,
    { ajaxUrl, searchableFields, dataPath, template, el },
  ) {
    if (!ajaxUrl) {
      callback([]);
      return;
    }

    const searchValue = query?.trim() || null;
    const params = buildSearchParams(searchableFields, searchValue);
    const url = buildUrl(ajaxUrl, params);

    const data = await tomSelectApi.fetch(url);

    if (!data) {
      callback([]);
      return;
    }

    const values = normalizeData(data, dataPath);
    const results = template.getResults(values, el).results;
    callback(results.map((r) => ({ ...r })));
  }

  /**
   * Setup events
   */
  #setupEvents(el, instance) {
    instance.on('item_add', (value) => {
      const detail = instance.options[value] ?? null;
      el.dispatchEvent(
        new CustomEvent('chosen-option', { detail, bubbles: true }),
      );
    });
  }

  /**
   * Load default value
   */
  async #loadDefaultValue(el, instance, config) {
    const { defaultValue, ajaxUrl, searchableFields, dataPath, template } =
      config;

    if (!defaultValue || !ajaxUrl) return;

    const params = buildSearchParams(searchableFields, defaultValue);
    const url = buildUrl(ajaxUrl, params);
    const data = await tomSelectApi.fetch(url);

    if (!data) return;

    const values = normalizeData(data, dataPath);
    template.setRecentSelected?.(values, el, defaultValue);

    const results = template.getResults(values, el).results;
    const found = results.find((r) => String(r.id) === String(defaultValue));

    if (found) {
      instance.addOption(found);
      instance.setValue(String(found.id), true);
    }
  }

  /**
   * Apply initial configuration to elements
   */
  #applyConfigToNodes() {
    SELECT2_ELEMENTS.forEach((config) => {
      const nodes = document.querySelectorAll(config.selector);
      nodes.forEach((node) => {
        if (config.placeholder && !node.getAttribute('data-placeholder')) {
          node.setAttribute('data-placeholder', config.placeholder);
        }
        if (config.tags && !node.getAttribute('data-tags')) {
          node.setAttribute('data-tags', 'true');
        }
      });
    });
  }

  /**
   * Initialize all select2 elements
   */
  initAll() {
    this.#applyConfigToNodes();
    this.create('.select2-standard', 'default');
    this.create('.select2-template');
  }

  /**
   * Initialize select2 elements inside modal
   */
  initInModal(modalSelector) {
    this.create(`${modalSelector} .select2`);
    this.create(`${modalSelector} .select2-template`);
    this.create(`${modalSelector} .select2-standard`, 'default');
  }
}

export const tomSelectService = new TomSelectService();

export const select2Base = (selector, template) =>
  tomSelectService.create(selector, template);
export const select2Template = () =>
  tomSelectService.create('.select2-template');
export const select2Products = () =>
  tomSelectService.create('.select2-products', 'products');
export const select2Standard = () =>
  tomSelectService.create('.select2-standard', 'default');
export const initAllSelect2 = () => tomSelectService.initAll();
export const afterLoadModalInitSelect2 = (modal) =>
  tomSelectService.initInModal(modal);
