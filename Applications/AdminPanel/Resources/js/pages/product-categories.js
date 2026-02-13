/**
 * Product Categories JavaScript - AdminPanel
 * Handles category modal (create/edit)
 * Uses project standard AJAX system and ui-components
 */

import { uiComponents } from '@shared-js/ui-components.js';

const CATEGORY_MODAL_ID = 'categoryModal';
const CATEGORY_FORM_ID = 'categoryForm';

let editingCategoryId = null;

/**
 * Open create category modal
 */
const openCreateCategoryModal = () => {
  editingCategoryId = null;

  const form = document.getElementById(CATEGORY_FORM_ID);
  if (form) {
    form.reset();
    form.setAttribute('action', form.dataset.storeUrl || '');
    form.setAttribute('data-method', 'POST');

    const methodInput = form.querySelector('input[name="_method"]');
    if (methodInput) methodInput.remove();
  }

  const title = document.getElementById('categoryModalTitle');
  if (title) title.textContent = 'افزودن دسته‌بندی';

  // Reset icon selection
  document.querySelectorAll('#categoryModal .icon-option input').forEach((input) => {
    input.checked = false;
  });

  uiComponents.openModal(CATEGORY_MODAL_ID);
};

/**
 * Open edit category modal
 */
const openEditCategoryModal = (categoryData) => {
  editingCategoryId = categoryData.id;

  const form = document.getElementById(CATEGORY_FORM_ID);
  if (form) {
    form.reset();
    form.setAttribute(
      'action',
      form.dataset.updateUrl?.replace(':id', categoryData.id) || '',
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

  const title = document.getElementById('categoryModalTitle');
  if (title) title.textContent = 'ویرایش دسته‌بندی';

  // Populate fields
  const nameInput = form?.querySelector('[name="name"]');
  if (nameInput) nameInput.value = categoryData.name || '';

  const slugInput = form?.querySelector('[name="slug"]');
  if (slugInput) slugInput.value = categoryData.slug || '';

  const parentSelect = form?.querySelector('[name="parent_id"]');
  if (parentSelect) parentSelect.value = categoryData.parent_id || '';

  const sortInput = form?.querySelector('[name="sort_order"]');
  if (sortInput) sortInput.value = categoryData.sort_order ?? 0;

  const descInput = form?.querySelector('[name="description"]');
  if (descInput) descInput.value = categoryData.description.full ?? '';

  // Set icon
  if (categoryData.icon) {
    const iconInput = form?.querySelector(
      `input[name="icon"][value="${categoryData.icon}"]`,
    );
    if (iconInput) iconInput.checked = true;
  }

  // Set status
  const statusRadio = form?.querySelector(
    `input[name="is_active"][value="${categoryData.is_active ? '1' : '0'}"]`,
  );
  if (statusRadio) statusRadio.checked = true;

  uiComponents.openModal(CATEGORY_MODAL_ID);
};

/**
 * Initialize event listeners
 */
const initProductCategories = () => {
  window.openCreateCategoryModal = openCreateCategoryModal;
  window.openEditCategoryModal = openEditCategoryModal;

  const modal = document.getElementById(CATEGORY_MODAL_ID);
  modal?.addEventListener('modal:closed', () => {
    editingCategoryId = null;
  });
};

document.addEventListener('DOMContentLoaded', initProductCategories, {
  once: true,
});
