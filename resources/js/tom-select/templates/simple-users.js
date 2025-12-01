import { baseTemplate, createAvatar } from './helpers';
import users from './users';

const tpl = {
  ...baseTemplate,
  selection: (data) => users.selection(data),
  template: (data) => {
    const avatar = createAvatar(data.avatarUrl);
    return `<div class="select2-custom-option">${avatar}${data.displayName}</div>`;
  },
  getResults: (data) => users.getResults(data),
};

export default tpl;
