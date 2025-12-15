const defaultTemplate = {
  mapResults: (items, valueField, labelField) =>
    items.map((item) => ({
      [valueField]: item[valueField] ?? item.key ?? item.id,
      [labelField]: item[labelField] ?? item.value ?? item.name ?? item.title,
      ...item,
    })),

  render: {
    option: (data, escape) => {
      const label = data.name || data.text || data.title || data.label || '';
      return `<div class="ts-option-item">${escape(label)}</div>`;
    },
    item: (data, escape) => {
      const label = data.name || data.text || data.title || data.label || '';
      return `<div class="ts-selected-item">${escape(label)}</div>`;
    },
  },
};

export default defaultTemplate;
