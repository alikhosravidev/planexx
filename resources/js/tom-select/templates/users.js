const usersTemplate = {
  mapResults: (items, valueField, labelField) =>
    items.map((item) => ({
      [valueField]: item.id,
      [labelField]: item.full_name || item.display_name || item.name,
      avatar: item.avatar_image_url || item.avatar?.file_url || null,
      mobile: item.mobile || null,
      email: item.email || null,
      ...item,
    })),

  render: {
    option: (data, escape) => {
      const name = data.full_name || data.display_name || data.name || '';
      const avatar = data.avatar
        ? `<img src="${escape(data.avatar)}" class="ts-user-avatar" alt="" />`
        : `<span class="ts-user-avatar-placeholder"><i class="fa-solid fa-user"></i></span>`;

      return `
        <div class="ts-user-option">
          ${avatar}
          <div class="ts-user-info">
            <span class="ts-user-name">${escape(name)}</span>
            ${data.mobile ? `<span class="ts-user-detail"><i class="fa-solid fa-phone"></i> ${escape(data.mobile)}</span>` : ''}
          </div>
        </div>`;
    },
    item: (data, escape) => {
      const name = data.full_name || data.display_name || data.name || '';
      const avatar = data.avatar
        ? `<img src="${escape(data.avatar)}" class="ts-user-avatar-sm" alt="" />`
        : '';

      return `<div class="ts-user-selected">${avatar}<span>${escape(name)}</span></div>`;
    },
  },
};

export default usersTemplate;
