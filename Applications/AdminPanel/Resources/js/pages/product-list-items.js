/**
 * Product Custom List Items JavaScript - AdminPanel
 * Handles item add form, edit modal, delete confirmation
 * Uses project standard AJAX system and ui-components
 */

import { uiComponents } from '@shared-js/ui-components.js';

const EDIT_ITEM_MODAL_ID = 'editItemModal';
const EDIT_ITEM_FORM_ID = 'editItemForm';

let editingItemId = null;

/**
 * Open edit item modal
 */
const openEditItemModal = (itemData) => {
  editingItemId = itemData.id;

  const form = document.getElementById(EDIT_ITEM_FORM_ID);
  if (form) {
    form.reset();
    form.setAttribute(
      'action',
      form.dataset.updateUrl?.replace(':id', itemData.id) || '',
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

  // Populate fields
  const titleInput = form?.querySelector('[name="title"]');
  if (titleInput) titleInput.value = itemData.title || '';

  const codeInput = form?.querySelector('[name="code"]');
  if (codeInput) codeInput.value = itemData.code || '';

  // Populate custom fields
  for (let i = 1; i <= 5; i++) {
    const fieldInput = form?.querySelector(`[name="field${i}_value"]`);
    if (fieldInput) fieldInput.value = itemData[`field${i}_value`] || '';
  }

  // Set status
  const statusRadio = form?.querySelector(
    `input[name="is_active"][value="${itemData.is_active ? '1' : '0'}"]`,
  );
  if (statusRadio) statusRadio.checked = true;

  uiComponents.openModal(EDIT_ITEM_MODAL_ID);
};

/**
 * Initialize event listeners
 */
const initProductListItems = () => {
  window.openEditItemModal = openEditItemModal;

  const modal = document.getElementById(EDIT_ITEM_MODAL_ID);
  modal?.addEventListener('modal:closed', () => {
    editingItemId = null;
  });
};

document.addEventListener('DOMContentLoaded', initProductListItems, {
  once: true,
});
