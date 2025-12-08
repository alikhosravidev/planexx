class FilterBuilderService {
  constructor(baseURL) {
    this.baseURL = baseURL;
    this._filters = {}; // will map field => value | {op:value} | []
    this._searchTerm = null;
    this._searchFields = new Set();
    this._searchJoin = 'or'; // default aligns with BaseAPIController
    this._sorts = []; // array of {field, direction}
    this._includes = [];
    this._page = null;
    this._perPage = null;
    this._extra = {};
  }

  validateField(field) {
    if (typeof field !== 'string' || !field.trim()) {
      throw new Error('Field name must be a non-empty string.');
    }
  }

  validateValue(value) {
    if (value === null || value === undefined) {
      throw new Error('Value cannot be null or undefined.');
    }
  }

  // Filters (mapped to query filter[field])
  addRangeFilter(field, min, max) {
    this.validateField(field);
    this._filters[field] = {
      ...(this._filters[field] || {}),
      gte: min ?? undefined,
      lte: max ?? undefined,
    };
    return this;
  }

  addMultipleValueFilter(field, values) {
    this.validateField(field);
    if (!Array.isArray(values) || values.length === 0) {
      throw new Error('Values must be a non-empty array.');
    }
    this._filters[field] = { ...(this._filters[field] || {}), in: values };
    return this;
  }

  addComparisonFilter(field, operator, value) {
    this.validateField(field);
    this.validateValue(value);
    const valid = ['>', '<', '>=', '<=', '=', '!=', 'like'];
    if (!valid.includes(operator)) {
      throw new Error('Invalid comparison operator.');
    }
    if (operator === '=') {
      this._filters[field] = value;
    } else if (operator === '!=') {
      this._filters[field] = {
        ...(this._filters[field] || {}),
        not_in: Array.isArray(value) ? value : [value],
      };
    } else if (operator === 'like') {
      this._filters[field] = {
        ...(this._filters[field] || {}),
        like: value,
      };
    } else if (operator === '>=') {
      this._filters[field] = {
        ...(this._filters[field] || {}),
        gte: value,
      };
    } else if (operator === '<=') {
      this._filters[field] = {
        ...(this._filters[field] || {}),
        lte: value,
      };
    } else if (operator === '>') {
      this._filters[field] = {
        ...(this._filters[field] || {}),
        gt: value,
      };
    } else if (operator === '<') {
      this._filters[field] = {
        ...(this._filters[field] || {}),
        lt: value,
      };
    }
    return this;
  }

  // Search (mapped to search/searchFields/searchJoin)
  addSearchFilter(field, value) {
    this.validateField(field);
    this.validateValue(value);
    this._searchTerm = String(value);
    this._searchFields.add(field);
    return this;
  }

  hasRelatedWithRangeFilter(relation, field, min, max) {
    return this.addRangeFilter(`${relation}.${field}`, min, max);
  }

  hasRelatedWithMultipleValueFilter(relation, field, values) {
    return this.addMultipleValueFilter(`${relation}.${field}`, values);
  }

  hasRelatedWithComparisonFilter(relation, field, operator, value) {
    return this.addComparisonFilter(`${relation}.${field}`, operator, value);
  }

  hasRelatedWithSearchFilter(relation, field, value) {
    return this.addSearchFilter(`${relation}.${field}`, value);
  }

  // Sorting
  addSort(field, direction) {
    this.validateField(field);
    if (!['asc', 'desc'].includes(direction)) {
      throw new Error("Sort direction must be either 'asc' or 'desc'.");
    }
    this._sorts.push({ field, direction });
    return this;
  }

  // Includes
  includeRelation(relations) {
    if (!Array.isArray(relations) || relations.length === 0) {
      throw new Error('Relations must be a non-empty array.');
    }
    this._includes = relations;
    return this;
  }

  // Pagination
  setPage(page) {
    if (page < 1) {
      throw new Error('Page must be a positive integer.');
    }
    this._page = page;
    return this;
  }

  setPerPage(perPage) {
    if (perPage < 1) {
      throw new Error('Per page must be a positive integer.');
    }
    this._perPage = perPage;
    return this;
  }

  setOrJoin() {
    this._searchJoin = 'or';
    return this;
  }

  setAndJoin() {
    this._searchJoin = 'and';
    return this;
  }

  otherParams(field, value) {
    this._extra[field] = value;
    return this;
  }

  // Returns a flat object for common params (search/includes/sort/pagination)
  getFilters() {
    const out = { ...this._extra };
    if (this._searchTerm) {
      out.search = this._searchTerm;
      if (this._searchFields.size > 0) {
        out.searchFields = Array.from(this._searchFields).join(',');
      }
      out.searchJoin = this._searchJoin;
    }
    if (this._includes.length > 0) {
      out.includes = this._includes.join(',');
    }
    if (this._sorts.length > 0) {
      // Prefer JSON:API style sort param: field,-created_at
      const sortValue = this._sorts
        .map((s) => (s.direction === 'desc' ? `-${s.field}` : s.field))
        .join(',');
      out.sort = sortValue;
    }
    if (this._page != null) out.page = this._page;
    if (this._perPage != null) out.per_page = this._perPage;
    return out;
  }

  // Build full URL with proper serialization including filter[...] nesting
  buildURL() {
    const params = new URLSearchParams();

    // flat params
    const flat = this.getFilters();
    Object.entries(flat).forEach(([k, v]) => {
      if (Array.isArray(v)) {
        v.forEach((vv) => params.append(`${k}[]`, String(vv)));
      } else if (v != null) {
        params.append(k, String(v));
      }
    });

    // nested filters
    Object.entries(this._filters).forEach(([field, val]) => {
      if (val == null) return;
      if (typeof val !== 'object' || Array.isArray(val)) {
        // simple equality
        params.append(
          `filter[${field}]`,
          Array.isArray(val) ? val.join(',') : String(val),
        );
      } else {
        // operators
        Object.entries(val).forEach(([op, v]) => {
          if (v == null) return;
          if (Array.isArray(v)) {
            v.forEach((item) =>
              params.append(`filter[${field}][${op}][]`, String(item)),
            );
          } else {
            params.append(`filter[${field}][${op}]`, String(v));
          }
        });
      }
    });

    const qs = params.toString();
    if (!this.baseURL) return qs ? `?${qs}` : '';
    if (!qs) return this.baseURL;
    return this.baseURL.includes('?')
      ? `${this.baseURL}&${qs}`
      : `${this.baseURL}?${qs}`;
  }
}

export const filterBuilderService = FilterBuilderService;
