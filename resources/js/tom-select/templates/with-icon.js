import { baseTemplate } from './helpers';

const tpl = {
  ...baseTemplate,
  selection: (data) => `
    <div class="select2-template with-icon selected">
      <i class="icon ${data.class}"></i>
      <span class="title">${data.title}</span>
    </div>`,
  template: (data) => `
    <div class="select2-template with-icon">
      <i class="icon ${data.class}"></i>
      <span class="title">${data.title}</span>
    </div>`,
  getResults: (data) => ({
    results: data.map(item => ({
      id: item.icon ? `${item.icon}_${item.title}_${item.url}` : item.class,
      title: item.title || item.class,
      class: item.icon || item.class,
    })),
  }),
};

export default tpl;
