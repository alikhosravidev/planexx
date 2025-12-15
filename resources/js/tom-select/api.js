import { httpClient } from '@resources/js/api/http-client.js';

class TomSelectApi {
  async fetch(url, params = {}) {
    if (!url) return null;
    try {
      const response = await httpClient.get(url, { params });
      return response.data;
    } catch (error) {
      console.error('TomSelect API Error:', error);
      return null;
    }
  }

  async search(url, query, searchFields = null) {
    if (!url) return null;

    const params = {};
    if (query?.trim()) {
      if (searchFields) {
        params.search = query.trim();
        params.search_fields = searchFields;
      } else {
        params.s = query.trim();
      }
    }

    return this.fetch(url, params);
  }
}

export const tomSelectApi = new TomSelectApi();
