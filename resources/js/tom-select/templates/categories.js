import { baseTemplate, toggleCategoryButtons } from './helpers';

const tpl = {
  ...baseTemplate,
  getResults: (data) => {
    if (data.length === 0) {
      toggleCategoryButtons(true);
    }
    return {
      results: data.map(item => ({ id: item.id, text: `${item.id} > ${item.name_fa}` })),
    };
  },
  setRecentSelected: (data, element, defaultValue) => {
    if (!data.find(v => v.id == defaultValue)) {
      console.warn('Default value not found:', defaultValue);
    }
  },
};

export default tpl;
