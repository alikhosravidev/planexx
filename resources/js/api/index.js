// AJAX infrastructure entrypoint
// Initializes the declarative AJAX handler once on DOM ready

import { initializeAjaxHandler } from '@/api/ajax-handler.js';

document.addEventListener('DOMContentLoaded', initializeAjaxHandler, {
  once: true,
});
