import { baseTemplate } from './helpers';

const tpl = {
  ...baseTemplate,
  getResults: (data) => ({
    results: data.map((item) => ({ id: item.key, text: item.value })),
  }),
  setRecentSelected: (data, element, defaultValue) => {
    const item = data.find((v) => v.key == defaultValue);
    if (!item) console.warn('Default value not found:', defaultValue);
  },
};

export default tpl;
