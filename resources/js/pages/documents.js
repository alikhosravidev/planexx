/**
 * Document Management JavaScript
 * Handles file-specific features like drag & drop and folder preview
 * Note: Modal management is handled by the global ui-components.js
 */

// File Drop Zone
const initDropZone = () => {
  const dropZone = document.querySelector('[data-drop-zone]');
  const fileInput = document.querySelector('[data-file-input]');

  if (!dropZone || !fileInput) return;

  const highlightClasses = ['border-primary', 'bg-primary/10'];

  // Prevent default drag behaviors
  ['dragenter', 'dragover', 'dragleave', 'drop'].forEach((eventName) => {
    dropZone.addEventListener(eventName, preventDefaults, false);
    document.body.addEventListener(eventName, preventDefaults, false);
  });

  function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
  }

  // Highlight drop zone when item is dragged over it
  ['dragenter', 'dragover'].forEach((eventName) => {
    dropZone.addEventListener(
      eventName,
      () => {
        dropZone.classList.add(...highlightClasses);
      },
      false,
    );
  });

  ['dragleave', 'drop'].forEach((eventName) => {
    dropZone.addEventListener(
      eventName,
      () => {
        dropZone.classList.remove(...highlightClasses);
      },
      false,
    );
  });

  // Handle dropped files
  dropZone.addEventListener(
    'drop',
    (e) => {
      const dt = e.dataTransfer;
      const files = dt.files;

      if (files.length > 0) {
        fileInput.files = files;
        handleFileSelect(files[0], dropZone, highlightClasses);
      }
    },
    false,
  );

  // Handle file input change
  fileInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
      handleFileSelect(e.target.files[0], dropZone, highlightClasses);
    }
  });
};

// Handle file selection
const handleFileSelect = (file, dropZone, highlightClasses = []) => {
  if (dropZone && highlightClasses.length) {
    dropZone.classList.add(...highlightClasses);
  }
};

// Toggle temporary filter and refresh page with query param
const initTemporaryToggle = () => {
  const toggle = document.getElementById('is_temporary');
  if (!toggle) return;

  toggle.addEventListener('change', (event) => {
    const params = new URLSearchParams(window.location.search);

    if (event.target.checked) {
      params.set('is_temporary', '1');
    } else {
      params.delete('is_temporary');
    }

    const query = params.toString();
    const url = query ? `${window.location.pathname}?${query}` : window.location.pathname;
    window.location.href = url;
  });
};

// Initialize all features
const initDocuments = () => {
  initDropZone();
  initTemporaryToggle();
};

// Run on DOM ready
document.addEventListener('DOMContentLoaded', initDocuments);

export { initDocuments };
