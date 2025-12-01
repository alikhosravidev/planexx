import { baseTemplate, createAvatar, fallback } from './helpers';

const tpl = {
  ...baseTemplate,
  selection: (data) => {
    if (data.text) return data.text;
    if (!data.displayName) return '';
    const avatar = createAvatar(data.avatarUrl);
    return `<span class="select2-custom-option">${avatar}${data.displayName}</span>`;
  },
  template: (data) => {
    const avatar = createAvatar(data.avatarUrl);
    return `
      <div class="select2-template users-template">
        <div class="header">
          <span class="select2-custom-option">${avatar}${data.displayName}</span>
          <span class="text"><i class="si-smartphone-r icon"></i>${fallback(data.mobile)}</span>
        </div>
        <div class="email-section">
          <i class="si-email-r icon"></i>
          <span class="text">${fallback(data.email)}</span>
        </div>
      </div>`;
  },
  getResults: (data) => ({
    results: data.map(item => ({
      id: item.id,
      avatarUrl: item.avatar_image_url,
      displayName: item.display_name,
      mobile: item.mobile,
      email: item.email,
    })),
  }),
  setRecentSelected: (data, element, defaultValue) => {
    if (!data.find(v => v.id == defaultValue)) {
      console.warn('Default value not found:', defaultValue);
    }
  },
};

export default tpl;
