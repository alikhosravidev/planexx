/**
 * Task Modal Helper - Shared Utilities
 * Contains common functionality for task modals across AdminPanel and PWA
 */

import { get } from '@shared-js/api/request.js';
import { getTomSelectInstance } from '@shared-js/tom-select/index.js';

/**
 * Create DOM element getters object
 * @param {string} modalId - The ID of the modal element
 * @param {string} formId - The ID of the form element
 * @returns {Object} Object with getter properties for DOM elements
 */
export function createElementsGetters(modalId, formId) {
  return {
    get modal() {
      return document.getElementById(modalId);
    },
    get form() {
      return document.getElementById(formId);
    },
    get step1() {
      return document.getElementById('taskStep1');
    },
    get step2() {
      return document.getElementById('taskStep2');
    },
    get footer() {
      return document.getElementById('taskModalFooter');
    },
    get workflowOptions() {
      return document.getElementById('workflowOptions');
    },
    get workflowLoading() {
      return document.getElementById('workflowLoading');
    },
    get workflowError() {
      return document.getElementById('workflowError');
    },
    get selectedWorkflowName() {
      return document.getElementById('selectedWorkflowName');
    },
    get selectedWorkflowId() {
      return document.getElementById('selectedWorkflowId');
    },
    get assigneeSelect() {
      return document.querySelector(`#${formId} [name="assignee"]`);
    },
    get changeWorkflowBtn() {
      return document.getElementById('changeWorkflowBtn');
    },
    get modalTitle() {
      return document.getElementById(modalId.replace('Modal', 'ModalTitle'));
    },
    get submitBtnText() {
      return document.getElementById('submitTaskBtnText');
    },
  };
}

/**
 * Load workflows from API
 * @param {string} apiContext - 'admin' or 'client'
 * @param {Object} elements - DOM elements object
 * @returns {Promise<Array>} Array of workflows
 */
export async function loadWorkflows(apiContext, elements) {
  try {
    if (elements.workflowLoading) {
      elements.workflowLoading.classList.remove('hidden');
    }
    if (elements.workflowError) {
      elements.workflowError.classList.add('hidden');
    }

    const url = window.route(`api.v1.${apiContext}.bpms.workflows.index`, {
      per_page: 100,
      field: 'name',
      with_states: 1,
      includes: 'department',
      withCount: 'states',
    });

    const response = await get(url).execute();
    return response;
  } catch (error) {
    console.error('Error loading workflows:', error);
    if (elements.workflowLoading) {
      elements.workflowLoading.classList.add('hidden');
    }
    if (elements.workflowError) {
      elements.workflowError.classList.remove('hidden');
    } else if (elements.workflowLoading) {
      elements.workflowLoading.innerHTML =
        '<p class="text-red-500 text-sm">خطا در بارگذاری فرایندها</p>';
    }
    throw error;
  }
}

/**
 * Navigate to step 2 (task details)
 * @param {Object} selectedWorkflow - The selected workflow object
 * @param {Object} elements - DOM elements object
 */
export function goToStep2(selectedWorkflow, elements) {
  if (!selectedWorkflow) return;

  elements.step1?.classList.add('hidden');
  elements.step2?.classList.remove('hidden');
  elements.footer?.classList.remove('hidden');

  if (elements.selectedWorkflowName) {
    elements.selectedWorkflowName.textContent = selectedWorkflow.name;
  }
  if (elements.selectedWorkflowId) {
    elements.selectedWorkflowId.value = selectedWorkflow.id;
  }
}

/**
 * Navigate to step 1 (workflow selection)
 * @param {Object} elements - DOM elements object
 */
export function goToStep1(elements) {
  elements.step1?.classList.remove('hidden');
  elements.step2?.classList.add('hidden');
  elements.footer?.classList.add('hidden');
}

/**
 * Update form action based on edit mode
 * @param {Object} elements - DOM elements object
 * @param {boolean} isEditMode - Whether in edit mode
 * @param {number|null} editTaskId - The task ID being edited
 * @param {string} apiContext - 'admin' or 'client'
 */
export function updateFormAction(elements, isEditMode, editTaskId, apiContext) {
  if (!elements.form) return;

  if (isEditMode && editTaskId) {
    elements.form.setAttribute(
      'action',
      window.route(`api.v1.${apiContext}.bpms.tasks.update`, {
        task: editTaskId,
      }),
    );
    elements.form.setAttribute('data-method', 'PUT');
  } else {
    elements.form.setAttribute(
      'action',
      window.route(`api.v1.${apiContext}.bpms.tasks.store`),
    );
    elements.form.setAttribute('data-method', 'POST');
  }
}

/**
 * Update modal title and button texts
 * @param {Object} elements - DOM elements object
 * @param {boolean} isEditMode - Whether in edit mode
 */
export function updateModalTexts(elements, isEditMode) {
  if (elements.modalTitle) {
    elements.modalTitle.textContent = isEditMode
      ? 'ویرایش کار'
      : 'افزودن کار جدید';
  }
  if (elements.submitBtnText) {
    elements.submitBtnText.textContent = isEditMode
      ? 'ذخیره تغییرات'
      : 'ایجاد کار';
  }
}

/**
 * Populate form with task data for editing
 * @param {Object} elements - DOM elements object
 * @param {Object} taskData - The task data to populate
 * @param {Array} workflowsData - Array of available workflows
 * @param {Function} selectWorkflowCallback - Callback to select workflow visually
 */
export function populateFormWithTask(
  elements,
  taskData,
  workflowsData,
  selectWorkflowCallback,
) {
  if (!elements.form || !taskData) return;

  const titleInput = elements.form.querySelector('[name="title"]');
  const descriptionInput = elements.form.querySelector('[name="description"]');
  const priorityInput = elements.form.querySelector('[name="priority"]');
  const dueDateInput = elements.form.querySelector('[name="due_date"]');
  const estimatedHoursInput = elements.form.querySelector(
    '[name="estimated_hours"]',
  );

  if (titleInput) titleInput.value = taskData.title || '';
  if (descriptionInput)
    descriptionInput.value = taskData.description?.full || '';
  if (priorityInput)
    priorityInput.value = taskData.priority?.value ?? taskData.priority ?? 1;
  if (dueDateInput) dueDateInput.value = taskData.due_date?.main || '';
  if (estimatedHoursInput)
    estimatedHoursInput.value = taskData.estimated_hours || '';

  if (taskData.workflow_id && workflowsData) {
    const selectedWorkflow = workflowsData.find(
      (w) => w.id == taskData.workflow_id,
    );
    if (selectedWorkflow && selectWorkflowCallback) {
      selectWorkflowCallback(selectedWorkflow, taskData.workflow_id);
    }
  }
}

/**
 * Set assignee default value in TomSelect
 * @param {Object} elements - DOM elements object
 * @param {Object} taskData - The task data with assignee info
 */
export function setAssigneeDefaultValue(elements, taskData) {
  if (!taskData?.assignee_id) return;

  const assigneeSelect = elements.assigneeSelect;
  if (!assigneeSelect) return;

  const tomSelectInstance = getTomSelectInstance(assigneeSelect);
  if (!tomSelectInstance) return;

  const assigneeLabel =
    taskData.assignee?.full_name || `User ${taskData.assignee_id}`;
  tomSelectInstance.addOption({
    id: taskData.assignee_id,
    label: assigneeLabel,
  });
  tomSelectInstance.setValue(taskData.assignee_id, false);
}

/**
 * Reset TomSelect assignee field
 * @param {Object} elements - DOM elements object
 */
export function resetAssigneeSelect(elements) {
  const assigneeSelect = elements.assigneeSelect;
  if (assigneeSelect) {
    const tomSelectInstance = getTomSelectInstance(assigneeSelect);
    if (tomSelectInstance) {
      tomSelectInstance.clear();
    }
  }
}
