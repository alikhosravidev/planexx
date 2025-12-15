const departmentsTemplate = {
  mapResults: (items, valueField, labelField) =>
    items.map((item) => ({
      [valueField]: item.id,
      [labelField]: item.name,
      icon: item.icon || 'fa-building',
      color: item.color || 'blue-500',
      parent: item.parent?.name || null,
      level: item.level || 0,
      ...item,
    })),

  render: {
    option: (data, escape) => {
      const name = data.name || '';
      const indent = data.level ? '&nbsp;'.repeat(data.level * 4) + '└─ ' : '';
      const icon = data.icon || 'fa-building';

      return `
        <div class="ts-department-option">
          <span class="ts-department-indent">${indent}</span>
          <i class="fa-solid ${icon} ts-department-icon"></i>
          <span class="ts-department-name">${escape(name)}</span>
        </div>`;
    },
    item: (data, escape) => {
      const name = data.name || '';
      const icon = data.icon || 'fa-building';

      return `
        <div class="ts-department-selected">
          <i class="fa-solid ${icon}"></i>
          <span>${escape(name)}</span>
        </div>`;
    },
  },
};

export default departmentsTemplate;
