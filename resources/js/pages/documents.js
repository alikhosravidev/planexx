/**
 * Document Management JavaScript
 * Handles file-specific features like drag & drop and folder preview
 * Note: Modal management is handled by the global ui-components.js
 */

// File Drop Zone
const initDropZone = () => {
  const dropZones = document.querySelectorAll('[data-drop-zone]');

  dropZones.forEach((dropZone) => {
    const fileInput = dropZone.querySelector('input[type="file"]');

    if (!fileInput) return;

    const highlightClasses = ['border-primary', 'bg-primary/10'];

    function preventDefaults(e) {
      e.preventDefault();
      e.stopPropagation();
    }

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach((eventName) => {
      dropZone.addEventListener(eventName, preventDefaults, false);
    });

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
          handleFileSelect(files[0], fileInput);
        }
      },
      false,
    );

    // Handle file input change
    fileInput.addEventListener('change', (e) => {
      if (e.target.files.length > 0) {
        handleFileSelect(e.target.files[0], fileInput);
      }
    });
  });
};

// Handle file selection and display file name
const handleFileSelect = (file, fileInput) => {
  const fileInputId = fileInput.id;

  if (fileInputId === 'followUpFileInput') {
    const placeholder = document.getElementById('followUpFilePlaceholder');
    const selected = document.getElementById('followUpFileSelected');
    const fileName = document.getElementById('followUpFileName');

    if (placeholder && selected && fileName) {
      fileName.textContent = file.name;
      placeholder.classList.add('hidden');
      selected.classList.remove('hidden');
    }
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
    const url = query
      ? `${window.location.pathname}?${query}`
      : window.location.pathname;
    window.location.href = url;
  });
};

// Initialize all features
const initDocuments = () => {
  initDropZone();
  initTemporaryToggle();

  document.querySelectorAll('[data-dropdown-toggle]').forEach((toggle) => {
    toggle.addEventListener('click', function (e) {
      e.stopPropagation();
      const targetId = this.getAttribute('data-dropdown-toggle');
      const dropdown = document.getElementById(targetId);
      document.querySelectorAll('[data-dropdown]').forEach((d) => {
        if (d.id !== targetId) d.classList.add('hidden');
      });
      dropdown?.classList.toggle('hidden');
    });
  });

  document.addEventListener('click', () => {
    document
      .querySelectorAll('[data-dropdown]')
      .forEach((d) => d.classList.add('hidden'));
  });
};

// Run on DOM ready
document.addEventListener('DOMContentLoaded', initDocuments);

export { initDocuments };
