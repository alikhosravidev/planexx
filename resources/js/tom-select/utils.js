export function getAttr(el, name, defaultValue = null) {
  if (!el) return defaultValue;
  const value = el.getAttribute(name);
  if (!value) return defaultValue;

  if (name === 'data-url') {
    return value.replace(/&amp;/g, '&');
  }

  return value !== null ? value : defaultValue;
}

export function getBoolAttr(el, name, defaultValue = false) {
  if (!el) return defaultValue;
  return el.hasAttribute(name);
}

export function getIntAttr(el, name, defaultValue = null) {
  if (!el) return defaultValue;
  const value = el.getAttribute(name);
  if (value === null) return defaultValue;
  const parsed = parseInt(value, 10);
  return isNaN(parsed) ? defaultValue : parsed;
}

export function getByPath(obj, path, defaultValue = null) {
  if (!obj || !path) return defaultValue;
  const result = path.split('.').reduce((acc, key) => acc?.[key], obj);
  return result !== undefined ? result : defaultValue;
}

export function normalizeData(data, dataPath) {
  if (!data) return [];

  if (dataPath) {
    const pathData = getByPath(data, dataPath);
    if (pathData) return Array.isArray(pathData) ? pathData : [pathData];
  }

  const possiblePaths = [
    data?.data?.data?.data,
    data?.data?.data,
    data?.data,
    data,
  ];

  for (const path of possiblePaths) {
    if (Array.isArray(path)) return path;
  }

  return Array.isArray(data) ? data : [];
}

export function escapeHtml(str) {
  if (!str) return '';
  const div = document.createElement('div');
  div.textContent = str;
  return div.innerHTML;
}
