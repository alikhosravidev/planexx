export function escapeHtml(str) {
  if (!str) return '';
  const div = document.createElement('div');
  div.textContent = str;
  return div.innerHTML;
}

export function createAvatar(url, fallbackIcon = 'fa-user') {
  if (url) {
    return `<img src="${escapeHtml(url)}" class="ts-avatar" alt="" />`;
  }
  return `<span class="ts-avatar-placeholder"><i class="fa-solid ${fallbackIcon}"></i></span>`;
}

export function fallback(value, defaultText = '-') {
  return value || defaultText;
}
