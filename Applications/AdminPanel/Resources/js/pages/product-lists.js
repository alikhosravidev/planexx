/**
 * Product Custom Lists JavaScript - AdminPanel
 * Handles list modal (create/edit), delete confirmation
 * Uses project standard AJAX system and ui-components
 */

import { uiComponents } from '@shared-js/ui-components.js';

const LIST_MODAL_ID = 'listModal';
const LIST_FORM_ID = 'listForm';
const DELETE_LIST_MODAL_ID = 'deleteListModal';

let editingListId = null;

/**
 * Open create list modal
 */
const openCreateListModal = () => {
  editingListId = null;

  const form = document.getElementById(LIST_FORM_ID);
  if (form) {
    form.reset();
    form.setAttribute('action', form.dataset.storeUrl || '');
    form.setAttribute('data-method', 'POST');

    const methodInput = form.querySelector('input[name="_method"]');
    if (methodInput) methodInput.remove();
  }

  const title = document.getElementById('listModalTitle');
  if (title) title.textContent = 'ایجاد لیست جدید';

  // Reset color selection
  document.querySelectorAll('.color-option input').forEach((input) => {
    input.checked = false;
  });
  const defaultColor = document.querySelector(
    '.color-option input[value="blue-500"]',
  );
  if (defaultColor) defaultColor.checked = true;

  // Reset icon selection
  document.querySelectorAll('.icon-option input').forEach((input) => {
    input.checked = false;
  });

  uiComponents.openModal(LIST_MODAL_ID);
};

/**
 * Open edit list modal
 */
const openEditListModal = (listData) => {
  editingListId = listData.id;

  const form = document.getElementById(LIST_FORM_ID);
  if (form) {
    form.reset();
    form.setAttribute(
      'action',
      form.dataset.updateUrl?.replace(':id', listData.id) || '',
    );
    form.setAttribute('data-method', 'PUT');

    let methodInput = form.querySelector('input[name="_method"]');
    if (!methodInput) {
      methodInput = document.createElement('input');
      methodInput.type = 'hidden';
      methodInput.name = '_method';
      form.appendChild(methodInput);
    }
    methodInput.value = 'PUT';
  }

  const title = document.getElementById('listModalTitle');
  if (title) title.textContent = 'ویرایش لیست';

  // Populate form fields
  const nameInput = form?.querySelector('[name="name"]');
  if (nameInput) nameInput.value = listData.name || '';

  const nameEnInput = form?.querySelector('[name="name_en"]');
  if (nameEnInput) nameEnInput.value = listData.name_en || '';

  // Set color
  if (listData.color) {
    const colorInput = form?.querySelector(
      `input[name="color"][value="${listData.color}"]`,
    );
    if (colorInput) colorInput.checked = true;
  }

  // Set icon
  if (listData.icon) {
    const iconInput = form?.querySelector(
      `input[name="icon"][value="${listData.icon}"]`,
    );
    if (iconInput) iconInput.checked = true;
  }

  // Populate custom fields
  for (let i = 1; i <= 5; i++) {
    const labelInput = form?.querySelector(
      `[name="field${i}_label"]`,
    );
    const typeInput = form?.querySelector(
      `[name="field${i}_type"]`,
    );
    if (labelInput) labelInput.value = listData[`field${i}_label`] || '';
    if (typeInput) typeInput.value = listData[`field${i}_type`] || 'text';
  }

  uiComponents.openModal(LIST_MODAL_ID);
};

/**
 * Initialize event listeners
 */
const initProductLists = () => {
  // Expose functions globally
  window.openCreateListModal = openCreateListModal;
  window.openEditListModal = openEditListModal;

  // Handle modal close reset
  const modal = document.getElementById(LIST_MODAL_ID);
  modal?.addEventListener('modal:closed', () => {
    editingListId = null;
  });
};

document.addEventListener('DOMContentLoaded', initProductLists, {
  once: true,
});
