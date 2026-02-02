/**
 * BPMS Tasks JavaScript - AdminPanel
 * Handles task modal functionality with multi-step workflow selection
 * Uses project standard AJAX system and ui-components
 */

import { uiComponents } from '@shared-js/ui-components.js';
import { initDocuments } from './documents.js';
import {
  createElementsGetters,
  loadWorkflows as loadWorkflowsHelper,
  goToStep1 as goToStep1Helper,
  goToStep2 as goToStep2Helper,
  updateFormAction as updateFormActionHelper,
  updateModalTexts as updateModalTextsHelper,
  populateFormWithTask as populateFormWithTaskHelper,
  setAssigneeDefaultValue as setAssigneeDefaultValueHelper,
  resetAssigneeSelect,
} from '@shared-js/bpms/task-modal-helper.js';

const MODAL_ID = 'taskModal';
const FORM_ID = 'taskForm';
const API_CONTEXT = 'admin';

let workflowsData = [];
let selectedWorkflow = null;
let isEditMode = false;
let editTaskId = null;

const elements = createElementsGetters(MODAL_ID, FORM_ID);

const loadWorkflows = async () => {
  try {
    workflowsData = await loadWorkflowsHelper(API_CONTEXT, elements);
    renderWorkflowOptions();
  } catch (error) {
    // Error already handled in helper
  }
};

const renderWorkflowOptions = () => {
  if (!elements.workflowLoading || !elements.workflowOptions) return;

  elements.workflowLoading.classList.add('hidden');
  elements.workflowOptions.innerHTML = workflowsData
    .map(
      (wf) => `
      <label class="workflow-option cursor-pointer" data-workflow-id="${wf.id}">
        <input type="radio" name="workflow_select" value="${wf.id}" class="hidden"
               data-name="${wf.name}">
        <div class="border-2 border-border-medium rounded-xl p-4 hover:border-indigo-300 transition-all">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
              <i class="fa-solid fa-diagram-project text-indigo-600"></i>
            </div>
            <div class="flex-1 min-w-0">
              <h4 class="text-base font-bold text-text-primary">${wf.name}</h4>
              <p class="text-xs text-text-muted">${wf.department?.name || ''} • ${wf.states_count} مرحله</p>
            </div>
          </div>
        </div>
      </label>
    `,
    )
    .join('');

  document.querySelectorAll('.workflow-option').forEach((option) => {
    option.addEventListener('click', function () {
      const input = this.querySelector('input');

      document.querySelectorAll('.workflow-option > div').forEach((d) => {
        d.classList.remove('border-indigo-600', 'bg-indigo-50');
      });
      this.querySelector('div').classList.add(
        'border-indigo-600',
        'bg-indigo-50',
      );
      input.checked = true;

      selectedWorkflow = workflowsData.find((w) => w.id == input.value);
      goToStep2();
    });
  });
};

const goToStep2 = () => {
  goToStep2Helper(selectedWorkflow, elements);
};

const goToStep1 = () => {
  goToStep1Helper(elements);
};

const resetModal = () => {
  elements.form?.reset();
  goToStep1();

  resetAssigneeSelect(elements);

  document
    .querySelectorAll('.workflow-option input')
    .forEach((i) => (i.checked = false));
  document.querySelectorAll('.workflow-option > div').forEach((d) => {
    d.classList.remove('border-indigo-600', 'bg-indigo-50');
  });

  selectedWorkflow = null;
  isEditMode = false;
  editTaskId = null;

  updateFormAction();
  updateModalTexts();
};

const updateFormAction = () => {
  updateFormActionHelper(elements, isEditMode, editTaskId, API_CONTEXT);
};

const updateModalTexts = () => {
  updateModalTextsHelper(elements, isEditMode);
};

const populateFormWithTask = (taskData) => {
  const selectWorkflowCallback = (workflow, workflowId) => {
    selectedWorkflow = workflow;
    const option = document.querySelector(
      `.workflow-option[data-workflow-id="${workflowId}"]`,
    );
    if (option) {
      option
        .querySelector('div')
        ?.classList.add('border-indigo-600', 'bg-indigo-50');
      const input = option.querySelector('input');
      if (input) input.checked = true;
    }
    goToStep2();
  };

  populateFormWithTaskHelper(
    elements,
    taskData,
    workflowsData,
    selectWorkflowCallback,
  );
};

const setAssigneeDefaultValue = (taskData) => {
  setAssigneeDefaultValueHelper(elements, taskData);
};

const openTaskModal = async (taskData = null) => {
  resetModal();

  if (taskData) {
    isEditMode = true;
    editTaskId = taskData.id;
    updateFormAction();
    updateModalTexts();
  }

  const handleModalOpened = () => {
    setTimeout(() => {
      if (taskData) {
        setAssigneeDefaultValue(taskData);
      }
    }, 100);
    elements.modal?.removeEventListener('modal:opened', handleModalOpened);
  };

  if (taskData) {
    elements.modal?.addEventListener('modal:opened', handleModalOpened);
  }

  uiComponents.openModal(MODAL_ID);

  await Promise.all([loadWorkflows()]);

  if (taskData) {
    populateFormWithTask(taskData);
  }
};

const initEventListeners = () => {
  document
    .getElementById('changeWorkflowBtn')
    ?.addEventListener('click', goToStep1);

  elements.modal?.addEventListener('modal:closed', () => {
    resetModal();
  });
};

const initBpmsTasks = () => {
  initEventListeners();
  initDocuments();

  window.openTaskModal = openTaskModal;

  window.openCreateTaskModal = () => openTaskModal(null);

  window.openEditTaskModal = (taskData) => openTaskModal(taskData);

  // Load workflows when modal opens (for data-modal-open usage)
  elements.modal?.addEventListener('modal:opened', () => {
    if (!isEditMode && !editTaskId) {
      loadWorkflows();
    }
  });
};

document.addEventListener('DOMContentLoaded', initBpmsTasks);

export { initBpmsTasks, openTaskModal };
