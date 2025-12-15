const keyValList = {
  mapResults: (data, valueField, labelField) => {
    data = data[0];

    return Object.entries(data).map(([id, name]) => ({
      [valueField]: id,
      [labelField]: name,
    }));
  },

  render: {
    option: (data, escape) => {
      const name = data.label || data.name || '';
      return `<div class="ts-user-option"><span class="ts-user-name">${escape(name)}</span></div>`;
    },
    item: (data, escape) => {
      const name = data.label || data.name || '';
      return `<div class="ts-user-selected"><span>${escape(name)}</span></div>`;
    },
  },
};

export default keyValList;
