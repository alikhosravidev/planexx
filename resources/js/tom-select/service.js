import TomSelect from 'tom-select';
import { tomSelectApi } from './api.js';
import { SELECTORS, DEFAULTS, DATA_ATTRIBUTES } from './config.js';
import {
  getAttr,
  getBoolAttr,
  getIntAttr,
  normalizeData,
  escapeHtml,
} from './utils.js';
import templates from './templates/index.js';

class TomSelectService {
  #instances = new Map();

  init(container = document) {
    this.#initEnhanced(container);
    this.#initAjax(container);
    this.#initTags(container);
    this.#initMultiple(container);
  }

  #initEnhanced(container) {
    container.querySelectorAll(SELECTORS.enhanced).forEach((el) => {
      if (this.#instances.has(el)) return;
      this.#createBasic(el);
    });
  }

  #initAjax(container) {
    container.querySelectorAll(SELECTORS.ajax).forEach((el) => {
      if (this.#instances.has(el)) return;
      this.#createAjax(el);
    });
  }

  #initTags(container) {
    container.querySelectorAll(SELECTORS.tags).forEach((el) => {
      if (this.#instances.has(el)) return;
      this.#createTags(el);
    });
  }

  #initMultiple(container) {
    container.querySelectorAll(SELECTORS.multiple).forEach((el) => {
      if (this.#instances.has(el)) return;
      this.#createMultiple(el);
    });
  }

  #createBasic(el) {
    const config = this.#getBaseConfig(el);
    const instance = new TomSelect(el, config);
    this.#instances.set(el, instance);
    this.#setupEvents(el, instance);
    return instance;
  }

  #createAjax(el) {
    const config = this.#getBaseConfig(el);
    const url = getAttr(el, DATA_ATTRIBUTES.url);
    const searchFields = getAttr(el, DATA_ATTRIBUTES.searchFields);
    const dataPath = getAttr(el, DATA_ATTRIBUTES.dataPath);
    const valueField = getAttr(el, DATA_ATTRIBUTES.valueField, 'id');
    const labelField = getAttr(el, DATA_ATTRIBUTES.labelField, 'name');
    const templateName = getAttr(el, DATA_ATTRIBUTES.template);
    const preload = getBoolAttr(el, DATA_ATTRIBUTES.preload);
    const defaultValue = getAttr(el, DATA_ATTRIBUTES.defaultValue);
    const minSearch = getIntAttr(
      el,
      DATA_ATTRIBUTES.minSearch,
      DEFAULTS.minSearchLength,
    );

    // Get template if specified, otherwise use null (no custom rendering)
    const template = templateName ? templates[templateName] : null;

    config.valueField = valueField;
    config.labelField = labelField;
    config.searchField = [labelField];

    let dataLoaded = false;

    config.load = async (query, callback) => {
      if (dataLoaded && query === '') {
        callback([]);
        return;
      }

      if (query.length < minSearch && query !== '') {
        callback([]);
        return;
      }

      const data = await tomSelectApi.search(url, query, searchFields);
      if (!data) {
        callback([]);
        return;
      }

      const items = normalizeData(data, dataPath);
      const results = template?.mapResults
        ? template.mapResults(items, valueField, labelField)
        : items.map((item) => ({
            [valueField]: item[valueField],
            [labelField]: item[labelField],
            ...item,
          }));

      const isFirstLoad = !dataLoaded && query === '';
      dataLoaded = true;

      callback(results);

      if (defaultValue && isFirstLoad) {
        setTimeout(() => {
          const instance = this.#instances.get(el);
          if (instance) {
            instance.setValue(defaultValue, false);
          }
        }, 0);
      }
    };

    config.preload = preload ? true : 'focus';

    if (template?.render) {
      config.render = {
        option: (data, escape) => template.render.option(data, escape),
        item: (data, escape) => template.render.item(data, escape),
      };
    }

    const instance = new TomSelect(el, config);
    this.#instances.set(el, instance);
    this.#setupEvents(el, instance);

    return instance;
  }

  #createTags(el) {
    const config = this.#getBaseConfig(el);
    config.create = true;
    config.createOnBlur = DEFAULTS.createOnBlur;
    config.persist = DEFAULTS.persist;
    config.plugins = ['remove_button'];

    const maxItems = getIntAttr(el, DATA_ATTRIBUTES.maxItems);
    if (maxItems) config.maxItems = maxItems;

    const instance = new TomSelect(el, config);
    this.#instances.set(el, instance);
    this.#setupEvents(el, instance);
    return instance;
  }

  #createMultiple(el) {
    const config = this.#getBaseConfig(el);
    const maxItems = getIntAttr(el, DATA_ATTRIBUTES.maxItems);
    config.maxItems = maxItems || null;
    config.hideSelected = DEFAULTS.hideSelected;
    config.plugins = ['remove_button'];

    const instance = new TomSelect(el, config);
    this.#instances.set(el, instance);
    this.#setupEvents(el, instance);
    return instance;
  }

  #getBaseConfig(el) {
    const placeholder = getAttr(
      el,
      DATA_ATTRIBUTES.placeholder,
      DEFAULTS.placeholder,
    );

    return {
      placeholder,
      allowEmptyOption: true,
      maxOptions: DEFAULTS.maxOptions,
      loadThrottle: DEFAULTS.loadThrottle,
      closeAfterSelect: DEFAULTS.closeAfterSelect,
      selectOnTab: DEFAULTS.selectOnTab,
      render: {
        no_results: () => `<div class="ts-no-results">موردی یافت نشد</div>`,
        loading: () => `<div class="ts-loading">در حال بارگذاری...</div>`,
      },
    };
  }

  #setupEvents(el, instance) {
    instance.on('change', (value) => {
      el.dispatchEvent(
        new CustomEvent('tom-select:change', {
          detail: { value, option: instance.options[value] || null },
          bubbles: true,
        }),
      );
    });

    instance.on('item_add', (value, item) => {
      el.dispatchEvent(
        new CustomEvent('tom-select:add', {
          detail: { value, option: instance.options[value] || null, item },
          bubbles: true,
        }),
      );
    });

    instance.on('item_remove', (value) => {
      el.dispatchEvent(
        new CustomEvent('tom-select:remove', {
          detail: { value },
          bubbles: true,
        }),
      );
    });
  }

  #getInitialOptions(el, valueField, labelField) {
    const options = [];
    const items = [];

    const selectedOptions = el.querySelectorAll(
      'option[selected], option:checked',
    );
    selectedOptions.forEach((opt) => {
      if (opt.value) {
        options.push({
          [valueField]: opt.value,
          [labelField]: opt.textContent.trim(),
        });
        items.push(opt.value);
      }
    });

    return { options, items };
  }

  async #loadDefaultValue(
    el,
    instance,
    url,
    dataPath,
    valueField,
    labelField,
    template,
  ) {
    const defaultValue = getAttr(el, DATA_ATTRIBUTES.defaultValue);
    if (!defaultValue || !url) return;

    const data = await tomSelectApi.fetch(url, { [valueField]: defaultValue });
    if (!data) return;

    const items = normalizeData(data, dataPath);
    const found = items.find(
      (item) => String(item[valueField]) === String(defaultValue),
    );

    if (found) {
      const option = template.mapResults
        ? template.mapResults([found], valueField, labelField)[0]
        : {
            [valueField]: found[valueField],
            [labelField]: found[labelField],
            ...found,
          };

      instance.addOption(option);
      instance.setValue(String(found[valueField]), true);
    }
  }

  getInstance(el) {
    return this.#instances.get(el) || null;
  }

  destroy(el) {
    const instance = this.#instances.get(el);
    if (instance) {
      instance.destroy();
      this.#instances.delete(el);
    }
  }

  destroyAll(container = document) {
    container.querySelectorAll('select, input').forEach((el) => {
      this.destroy(el);
    });
  }

  refresh(el) {
    this.destroy(el);
    if (el.hasAttribute('data-tom-select')) {
      this.#createBasic(el);
    } else if (el.hasAttribute('data-tom-select-ajax')) {
      this.#createAjax(el);
    } else if (el.hasAttribute('data-tom-select-tags')) {
      this.#createTags(el);
    } else if (el.hasAttribute('data-tom-select-multiple')) {
      this.#createMultiple(el);
    }
  }
}

export const tomSelectService = new TomSelectService();

export function initTomSelect(container = document) {
  tomSelectService.init(container);
}

export function destroyTomSelect(el) {
  tomSelectService.destroy(el);
}

export function getTomSelectInstance(el) {
  return tomSelectService.getInstance(el);
}
