function flattenDepartments(items, level = 0) {
  const result = [];

  for (const item of items) {
    result.push({
      ...item,
      _level: level,
    });

    if (item.children?.length > 0) {
      result.push(...flattenDepartments(item.children, level + 1));
    }
  }

  return result;
}

const departmentsTemplate = {
  mapResults: (items, valueField, labelField) => {
    const flattened = flattenDepartments(items);

    return flattened.map((item) => ({
      [valueField]: item.id,
      [labelField]: item.name,
      icon: item.icon || 'fa-building',
      color: item.color || 'blue-500',
      type: item.type?.label || '',
      typeColor: item.type?.color || 'gray',
      level: item._level,
      hasImage: item.type?.has_image && item.thumbnail?.file_url,
      imageUrl: item.thumbnail?.file_url,
      ...item,
    }));
  },

  render: {
    option: (data, escape) => {
      const name = data.name || '';
      const level = data.level || 0;
      const icon = data.icon || 'fa-building';
      const paddingRight = level * 20;
      const hasImage = data.hasImage && data.imageUrl;
      const imageUrl = data.imageUrl;

      if (hasImage) {
        return `
          <div class="ts-department-option" style="padding-right: ${paddingRight + 8}px;">
            <img
              src="${imageUrl}"
              alt="${escape(name)}"
              class="ts-department-image"
              style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover;"
            />
            <span class="ts-department-name">${escape(name)}</span>
          </div>`;
      }

      return `
        <div class="ts-department-option" style="padding-right: ${paddingRight + 8}px;">
          <i class="fa-solid ${icon} ts-department-icon"></i>
          <span class="ts-department-name">${escape(name)}</span>
        </div>`;
    },
    item: (data, escape) => {
      const name = data.name || '';
      const icon = data.icon || 'fa-building';
      const hasImage = data.hasImage && data.imageUrl;
      const imageUrl = data.imageUrl;

      if (hasImage) {
        return `
          <div class="ts-department-selected">
            <img
              src="${imageUrl}"
              alt="${escape(name)}"
              class="ts-department-selected-image"
              style="width: 20px; height: 20px; border-radius: 50%; object-fit: cover;"
            />
            <span>${escape(name)}</span>
          </div>`;
      }

      return `
        <div class="ts-department-selected">
          <i class="fa-solid ${icon}"></i>
          <span>${escape(name)}</span>
        </div>`;
    },
  },
};

export default departmentsTemplate;
