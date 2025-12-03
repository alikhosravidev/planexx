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

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Highlight drop zone when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.add('border-primary', 'bg-primary/10');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.remove('border-primary', 'bg-primary/10');
        }, false);
    });

    // Handle dropped files
    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    }, false);

    // Handle file input change
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFileSelect(e.target.files[0]);
        }
    });
};

// Handle file selection
const handleFileSelect = (file) => {
    console.log('File selected:', file.name, file.size, file.type);
    // You can add file preview or validation here
};

// Folder Preview Update
const initFolderPreview = () => {
    const nameInput = document.querySelector('[data-folder-name-input]');
    const previewName = document.querySelector('[data-folder-preview-name]');
    const colorInputs = document.querySelectorAll('input[name="folder-color"]');
    const folderPreview = document.querySelector('[data-folder-preview]');

    // Update name preview
    nameInput?.addEventListener('input', (e) => {
        const value = e.target.value.trim();
        if (previewName) {
            previewName.textContent = value || 'نام پوشه';
        }
    });

    // Update color preview
    colorInputs?.forEach(input => {
        input.addEventListener('change', (e) => {
            if (e.target.checked && folderPreview) {
                const color = e.target.value;
                const colorMap = {
                    purple: { tab: '#A78BFA', main: '#7C3AED' },
                    pink: { tab: '#F472B6', main: '#EC4899' },
                    green: { tab: '#34D399', main: '#10B981' },
                    blue: { tab: '#60A5FA', main: '#3B82F6' },
                    amber: { tab: '#FBBF24', main: '#F59E0B' },
                    slate: { tab: '#94A3B8', main: '#64748B' }
                };

                const colors = colorMap[color] || colorMap.blue;
                const paths = folderPreview.querySelectorAll('path');
                if (paths.length >= 2) {
                    paths[0].setAttribute('fill', colors.tab);
                    paths[1].setAttribute('fill', colors.main);
                }
            }
        });
    });
};


// Custom AJAX Action: Toggle Favorite
const registerCustomActions = () => {
    if (typeof window.registerAction === 'function') {
        window.registerAction('toggleFavorite', (data, element) => {
            const icon = element.querySelector('i');
            if (data.is_favorite) {
                icon?.classList.remove('fa-regular');
                icon?.classList.add('fa-solid');
                element.classList.add('text-amber-500');
            } else {
                icon?.classList.remove('fa-solid');
                icon?.classList.add('fa-regular');
                element.classList.remove('text-amber-500');
            }
        });
    }
};

// Search Functionality
const initSearch = () => {
    const searchInput = document.querySelector('[data-search-input]');
    
    if (!searchInput) return;

    let searchTimeout;
    searchInput.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const url = new URL(window.location.href);
            if (e.target.value) {
                url.searchParams.set('search', e.target.value);
            } else {
                url.searchParams.delete('search');
            }
            window.location.href = url.toString();
        }, 500);
    });
};

// Loading State Management
const initLoadingStates = () => {
    // Handle form submission loading states
    document.addEventListener('ajax:before', (e) => {
        const form = e.target.closest('form[data-ajax]');
        if (!form) return;

        const submitBtn = form.querySelector('[data-submit-button]');
        if (!submitBtn) return;

        // Disable button
        submitBtn.disabled = true;

        // Toggle icons
        const icon = submitBtn.querySelector('[data-icon]');
        const loadingIcon = submitBtn.querySelector('[data-loading-icon]');
        const text = submitBtn.querySelector('[data-text]');
        const loadingText = submitBtn.querySelector('[data-loading-text]');

        if (icon) icon.classList.add('hidden');
        if (loadingIcon) loadingIcon.classList.remove('hidden');
        if (text) text.classList.add('hidden');
        if (loadingText) loadingText.classList.remove('hidden');
    });

    document.addEventListener('ajax:complete', (e) => {
        const form = e.target.closest('form[data-ajax]');
        if (!form) return;

        const submitBtn = form.querySelector('[data-submit-button]');
        if (!submitBtn) return;

        // Re-enable button
        submitBtn.disabled = false;

        // Toggle icons back
        const icon = submitBtn.querySelector('[data-icon]');
        const loadingIcon = submitBtn.querySelector('[data-loading-icon]');
        const text = submitBtn.querySelector('[data-text]');
        const loadingText = submitBtn.querySelector('[data-loading-text]');

        if (icon) icon.classList.remove('hidden');
        if (loadingIcon) loadingIcon.classList.add('hidden');
        if (text) text.classList.remove('hidden');
        if (loadingText) loadingText.classList.add('hidden');
    });
};

// Initialize all features
const initDocuments = () => {
    initDropZone();
    initFolderPreview();
    registerCustomActions();
    initSearch();
    initLoadingStates();
};

// Run on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDocuments);
} else {
    initDocuments();
}

export { initDocuments };
