/**
 * PWA Tasks Page JavaScript
 * Handles task creation modal with workflow selection
 * Uses shared helpers from resources/js/bpms/task-modal-helper.js
 */

import {
  createElementsGetters,
  loadWorkflows as loadWorkflowsHelper,
  goToStep1 as goToStep1Helper,
  goToStep2 as goToStep2Helper,
  updateFormAction as updateFormActionHelper,
  updateModalTexts as updateModalTextsHelper,
  resetAssigneeSelect,
} from '@shared-js/bpms/task-modal-helper.js';

const MODAL_ID = 'createTaskModal';
const FORM_ID = 'createTaskForm';
const API_CONTEXT = 'client';

let workflowsData = [];
let selectedWorkflow = null;

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

  if (!workflowsData || workflowsData.length === 0) {
    elements.workflowOptions.innerHTML = `
      <div class="text-center py-6 text-slate-500 text-sm">
        هیچ فرایندی یافت نشد
      </div>
    `;
    return;
  }

  elements.workflowOptions.innerHTML = workflowsData
    .map(
      (wf) => `
      <label class="workflow-option cursor-pointer block" data-workflow-id="${wf.id}">
        <input type="radio" name="workflow_select" value="${wf.id}" class="hidden" data-name="${wf.name}">
        <div class="border-2 border-gray-200 rounded-xl p-4 hover:border-slate-400 transition-all active:scale-[0.98]">
          <div class="flex items-center gap-3">
            <div class="w-11 h-11 bg-slate-100 rounded-xl flex items-center justify-center flex-shrink-0">
              <i class="fa-solid fa-diagram-project text-slate-700"></i>
            </div>
            <div class="flex-1 min-w-0">
              <h4 class="text-slate-900 text-sm font-bold truncate">${wf.name}</h4>
              <p class="text-slate-500 text-xs truncate">${wf.department?.name || ''} • ${wf.states_count} مرحله</p>
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

      // Reset all options
      document.querySelectorAll('.workflow-option > div').forEach((d) => {
        d.classList.remove('border-slate-900', 'bg-slate-50');
        d.classList.add('border-gray-200');
      });

      // Mark selected
      const div = this.querySelector('div');
      div.classList.remove('border-gray-200');
      div.classList.add('border-slate-900', 'bg-slate-50');
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
    d.classList.remove('border-slate-900', 'bg-slate-50');
    d.classList.add('border-gray-200');
  });

  selectedWorkflow = null;

  updateFormAction();
  updateModalTexts();
};

const updateFormAction = () => {
  updateFormActionHelper(elements, false, null, API_CONTEXT);
};

const updateModalTexts = () => {
  updateModalTextsHelper(elements, false);
};

const openCreateTaskModal = () => {
  resetModal();

  // For PWA - manually show modal
  if (elements.modal) {
    elements.modal.classList.remove('hidden');
    elements.modal.classList.add('flex');
  }

  loadWorkflows();
};

const closeModal = () => {
  if (elements.modal) {
    elements.modal.classList.add('hidden');
    elements.modal.classList.remove('flex');
  }
  setTimeout(() => resetModal(), 300);
};

const initEventListeners = () => {
  // Change workflow button
  document
    .getElementById('changeWorkflowBtn')
    ?.addEventListener('click', (e) => {
      e.preventDefault();
      goToStep1();
    });

  // Modal open buttons
  const openButtons = document.querySelectorAll(
    `[data-modal-open="${MODAL_ID}"]`,
  );
  openButtons.forEach((btn) => {
    btn.addEventListener('click', () => {
      openCreateTaskModal();
    });
  });

  // Modal close buttons
  const closeButtons = document.querySelectorAll(
    `#${MODAL_ID} [data-modal-close]`,
  );
  closeButtons.forEach((btn) => {
    btn.addEventListener('click', () => {
      closeModal();
    });
  });

  // Backdrop click to close
  elements.modal?.addEventListener('click', (e) => {
    if (e.target === elements.modal) {
      closeModal();
    }
  });
};

const initPWATasks = () => {
  initEventListeners();
};

document.addEventListener('DOMContentLoaded', initPWATasks);

export { initPWATasks, openCreateTaskModal };
