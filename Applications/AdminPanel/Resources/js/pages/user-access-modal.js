/**
 * User Access Modal JavaScript - AdminPanel
 * Handles role assignment modal functionality
 *
 * @module user-access-modal
 * @description Manages user role assignment through a modal interface.
 *              Uses modal state data instead of API calls for better performance.
 */

import {
  getTomSelectInstance,
  setTomSelectValueAsync,
} from '@shared-js/tom-select/index.js';
import { uiComponents } from '@shared-js/ui-components.js';

// ============================================================================
// Constants
// ============================================================================

const MODAL_ID = 'accessModal';

const SELECTORS = {
  modal: `#${MODAL_ID}`,
  form: 'form[data-ajax]',
  username: '[data-modal-username]',
  primaryRole: '#primary_role',
  secondaryRoles: 'input[name="secondary_roles[]"]',
};

// ============================================================================
// Main Initialization
// ============================================================================

/**
 * Initialize the access modal functionality
 */
const initAccessModal = () => {
  const modal = getModalElement();
  if (!modal) return;

  setupEventListeners(modal);
};

// ============================================================================
// Event Handlers
// ============================================================================

/**
 * Setup modal event listeners
 * @param {HTMLElement} modal - Modal element
 */
const setupEventListeners = (modal) => {
  modal.addEventListener('modal:opened', handleModalOpened);
  modal.addEventListener('modal:closed', () => handleModalClosed(modal));
};

/**
 * Handle modal opened event
 */
const handleModalOpened = async () => {
  const modal = getModalElement();
  const modalState = uiComponents.getModalState(MODAL_ID);

  if (!validateModalState(modalState)) return;

  const { userId, userName, primaryRole, secondaryRoles } = modalState;

  updateUsername(modal, userName);
  updateFormAction(modal, userId);

  await populateRoles(modal, primaryRole, secondaryRoles);
};

/**
 * Handle modal closed event
 * @param {HTMLElement} modal - Modal element
 */
const handleModalClosed = (modal) => {
  resetModal(modal);
};

// ============================================================================
// Modal State Management
// ============================================================================

/**
 * Validate modal state data
 * @param {Object} state - Modal state object
 * @returns {boolean} True if state is valid
 */
const validateModalState = (state) => {
  if (!state?.userId) {
    console.warn('[AccessModal] Invalid modal state: missing userId');
    return false;
  }
  return true;
};

/**
 * Update username display in modal header
 * @param {HTMLElement} modal - Modal element
 * @param {string} userName - User's full name
 */
const updateUsername = (modal, userName) => {
  const usernameEl = modal.querySelector(SELECTORS.username);
  if (usernameEl && userName) {
    usernameEl.textContent = userName;
  }
};

/**
 * Update form action URL with user ID
 * @param {HTMLElement} modal - Modal element
 * @param {number|string} userId - User ID
 */
const updateFormAction = (modal, userId) => {
  const form = modal.querySelector(SELECTORS.form);
  if (!form || !userId) return;

  const url = window.route('api.v1.admin.org.users.roles.update', {
    user: userId,
  });
  form.setAttribute('action', url);
};

// ============================================================================
// Role Population
// ============================================================================

/**
 * Populate role fields with user's existing roles
 * @param {HTMLElement} modal - Modal element
 * @param {number|null} primaryRole - Primary role ID
 * @param {number[]} secondaryRoles - Array of secondary role IDs
 */
const populateRoles = async (modal, primaryRole, secondaryRoles = []) => {
  await setPrimaryRole(modal, primaryRole);
  setSecondaryRoles(modal, secondaryRoles);
};

/**
 * Set primary role value in Tom-Select
 * @param {HTMLElement} modal - Modal element
 * @param {number|null} primaryRole - Primary role ID
 */
const setPrimaryRole = async (modal, primaryRole) => {
  if (primaryRole == null) return;

  const primarySelect = modal.querySelector(SELECTORS.primaryRole);
  if (!primarySelect) {
    console.error('[AccessModal] Primary role select element not found');
    return;
  }

  const success = await setTomSelectValueAsync(
    primarySelect,
    String(primaryRole),
    false,
  );

  if (!success) {
    console.error('[AccessModal] Failed to set primary role:', primaryRole);
  }
};

/**
 * Set secondary roles checkboxes
 * @param {HTMLElement} modal - Modal element
 * @param {number[]} secondaryRoles - Array of secondary role IDs
 */
const setSecondaryRoles = (modal, secondaryRoles = []) => {
  const checkboxes = modal.querySelectorAll(SELECTORS.secondaryRoles);
  const roleIdsStr = secondaryRoles.map(String);

  checkboxes.forEach((checkbox) => {
    checkbox.checked = roleIdsStr.includes(checkbox.value);
  });
};

// ============================================================================
// Modal Reset
// ============================================================================

/**
 * Reset modal to initial state
 * @param {HTMLElement} modal - Modal element
 */
const resetModal = (modal) => {
  resetForm(modal);
  resetPrimaryRole(modal);
  resetSecondaryRoles(modal);
  resetUsername(modal);
};

/**
 * Reset form element
 * @param {HTMLElement} modal - Modal element
 */
const resetForm = (modal) => {
  const form = modal.querySelector(SELECTORS.form);
  if (form) {
    form.reset();
    form.setAttribute('action', '#');
  }
};

/**
 * Reset primary role select
 * @param {HTMLElement} modal - Modal element
 */
const resetPrimaryRole = (modal) => {
  const primarySelect = modal.querySelector(SELECTORS.primaryRole);
  if (!primarySelect) return;

  const tomInstance = getTomSelectInstance(primarySelect);
  if (tomInstance) {
    tomInstance.clear();
  } else {
    primarySelect.value = '';
  }
};

/**
 * Reset all secondary role checkboxes
 * @param {HTMLElement} modal - Modal element
 */
const resetSecondaryRoles = (modal) => {
  const checkboxes = modal.querySelectorAll(SELECTORS.secondaryRoles);
  checkboxes.forEach((checkbox) => {
    checkbox.checked = false;
  });
};

/**
 * Clear username display
 * @param {HTMLElement} modal - Modal element
 */
const resetUsername = (modal) => {
  const usernameEl = modal.querySelector(SELECTORS.username);
  if (usernameEl) {
    usernameEl.textContent = '';
  }
};

// ============================================================================
// Utility Functions
// ============================================================================

/**
 * Get modal element from DOM
 * @returns {HTMLElement|null} Modal element or null if not found
 */
const getModalElement = () => {
  const modal = document.getElementById(MODAL_ID);
  if (!modal) {
    console.error('[AccessModal] Modal element not found');
    return null;
  }
  return modal;
};

// ============================================================================
// Initialization
// ============================================================================

document.addEventListener('DOMContentLoaded', initAccessModal);

// ============================================================================
// Exports
// ============================================================================

export { initAccessModal };
