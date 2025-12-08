import { filterBuilderService } from '@resources/js/services/filter-builder-service.js';

/**
 * Read attribute from element with support for snake_case and kebab-case
 */
export function getDataAttr(el, name) {
  if (!el) return undefined;

  const camelKey = name.replace(/[_-]([a-z])/g, (_, c) => c.toUpperCase());

  return el.dataset?.[camelKey];
}

/**
 * Safe access to nested value in object
 */
export function safeGetByPath(obj, path) {
  if (!obj || !path) return undefined;

  return path.split('.').reduce((acc, key) => acc?.[key], obj);
}

/**
 * Build search parameters
 */
export function buildSearchParams(searchableFields, searchValue) {
  if (!searchValue?.trim()) return {};

  const trimmedValue = searchValue.trim();

  if (!searchableFields) {
    return { s: trimmedValue };
  }

  const fieldsArray = searchableFields.split(',').map((f) => f.trim());
  const filter = new filterBuilderService('');

  fieldsArray.forEach((field) => filter.addSearchFilter(field, trimmedValue));

  return filter.setOrJoin().getFilters();
}

/**
 * Normalize received data
 */
export function normalizeData(data, dataPath) {
  if (dataPath && data?.[dataPath]) {
    return data[dataPath];
  }

  const possiblePaths = [
    data?.data?.terms,
    data?.data?.data?.data,
    data?.data?.data,
    data,
  ];

  return possiblePaths.find(Boolean) ?? data;
}

/**
 * Build URL with query string
 */
export function buildUrl(baseUrl, params) {
  if (!baseUrl) return null;

  const qs = new URLSearchParams(params).toString();
  return qs ? `${baseUrl}?${qs}` : baseUrl;
}
