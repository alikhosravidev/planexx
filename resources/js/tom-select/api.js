 import { httpClient } from '@resources/js/api/http-client.js';

 class TomSelectApi {
  async fetch(url) {
    if (!url) return null;
    try {
      const response = await httpClient.get(url);
      return response.data;
    } catch (error) {
      console.error('TomSelect API Error:', error);
      return null;
    }
  }
 }

 export const tomSelectApi = new TomSelectApi();
