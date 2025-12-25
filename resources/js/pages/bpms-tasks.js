/**
 * BPMS Tasks JavaScript
 * Handles task modal functionality with multi-step workflow selection
 * Uses project standard AJAX system and ui-components
 */

import { get } from '../api/request.js';
import { uiComponents } from '../ui-components.js';
// TODO: load upload file drop zone for all pages
import { initDocuments } from './documents.js';

const MODAL_ID = 'taskModal';

let workflowsData = [];
let usersData = [];
let selectedWorkflow = null;
let isEditMode = false;
let editTaskId = null;

const elements = {
  get modal() {
    return document.getElementById(MODAL_ID);
  },
  get form() {
    return document.getElementById('taskForm');
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
  get selectedWorkflowName() {
    return document.getElementById('selectedWorkflowName');
  },
  get selectedWorkflowId() {
    return document.getElementById('selectedWorkflowId');
  },
  get assigneeSearchInput() {
    return document.getElementById('assigneeSearchInput');
  },
  get assigneeDropdown() {
    return document.getElementById('assigneeDropdown');
  },
  get selectedAssigneeId() {
    return document.getElementById('selectedAssigneeId');
  },
  get selectedAssigneeDisplay() {
    return document.getElementById('selectedAssigneeDisplay');
  },
  get selectedAssigneeAvatar() {
    return document.getElementById('selectedAssigneeAvatar');
  },
  get selectedAssigneeName() {
    return document.getElementById('selectedAssigneeName');
  },
  get defaultAssigneeNotice() {
    return document.getElementById('defaultAssigneeNotice');
  },
  get modalTitle() {
    return document.getElementById('taskModalTitle');
  },
  get submitBtnText() {
    return document.getElementById('submitTaskBtnText');
  },
};

const loadWorkflows = async () => {
  try {
    const url = window.route('api.v1.admin.bpms.workflows.index', {
      per_page: 100,
      field: 'name',
      with_states: 1,
      includes: 'department',
      withCount: 'states',
    });

    const response = await get(url).execute();

    workflowsData = response;
    renderWorkflowOptions();
  } catch (error) {
    console.error('Error loading workflows:', error);
    if (elements.workflowLoading) {
      elements.workflowLoading.innerHTML =
        '<p class="text-red-500 text-sm">خطا در بارگذاری فرایندها</p>';
    }
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
               data-name="${wf.name}" data-states='${JSON.stringify(wf.states || [])}'>
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

const renderUserOptions = (filter = '') => {
  if (!elements.assigneeDropdown) return;

  const filtered = filter
    ? usersData.filter((u) =>
        (u.full_name || u.name || '')
          .toLowerCase()
          .includes(filter.toLowerCase()),
      )
    : usersData;

  elements.assigneeDropdown.innerHTML = filtered
    .map(
      (user) => `
      <div class="assignee-option flex items-center gap-3 px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors"
           data-id="${user.id}" data-name="${user.full_name || user.name}" data-avatar="${user.avatar?.file_url || ''}">
        <img src="${user.avatar?.file_url || '/images/default-avatar.png'}" alt="${user.full_name || user.name}" class="w-8 h-8 rounded-full object-cover">
        <div class="flex-1 min-w-0">
          <p class="text-sm font-medium text-text-primary">${user.full_name || user.name}</p>
          <p class="text-xs text-text-muted">${user.position?.name || ''}</p>
        </div>
      </div>
    `,
    )
    .join('');

  document.querySelectorAll('.assignee-option').forEach((opt) => {
    opt.addEventListener('click', function () {
      selectAssignee(
        this.dataset.id,
        this.dataset.name,
        this.dataset.avatar,
        false,
      );
      elements.assigneeDropdown.classList.add('hidden');
    });
  });
};

const goToStep2 = () => {
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

  const states = selectedWorkflow.states || [];
  const firstState = states.find((s) => s.position === 'start') || states[0];
  if (firstState?.default_assignee_id && !isEditMode) {
    const defaultUser = usersData.find(
      (u) => u.id === firstState.default_assignee_id,
    );
    if (defaultUser) {
      selectAssignee(
        defaultUser.id,
        defaultUser.full_name || defaultUser.name,
        defaultUser.avatar?.file_url || '',
        true,
      );
    }
  }
};

const goToStep1 = () => {
  elements.step1?.classList.remove('hidden');
  elements.step2?.classList.add('hidden');
  elements.footer?.classList.add('hidden');
};

const selectAssignee = (id, name, avatar, isDefault) => {
  if (elements.selectedAssigneeId) {
    elements.selectedAssigneeId.value = id;
  }
  if (elements.assigneeSearchInput) {
    elements.assigneeSearchInput.value = '';
  }
  if (elements.selectedAssigneeDisplay) {
    elements.selectedAssigneeDisplay.classList.remove('hidden');
  }
  if (elements.selectedAssigneeAvatar) {
    elements.selectedAssigneeAvatar.src =
      avatar || '/images/default-avatar.png';
  }
  if (elements.selectedAssigneeName) {
    elements.selectedAssigneeName.textContent = name;
  }

  if (elements.defaultAssigneeNotice) {
    if (isDefault) {
      elements.defaultAssigneeNotice.classList.remove('hidden');
    } else {
      elements.defaultAssigneeNotice.classList.add('hidden');
    }
  }
};

const clearAssignee = () => {
  if (elements.selectedAssigneeId) {
    elements.selectedAssigneeId.value = '';
  }
  if (elements.selectedAssigneeDisplay) {
    elements.selectedAssigneeDisplay.classList.add('hidden');
  }
  if (elements.defaultAssigneeNotice) {
    elements.defaultAssigneeNotice.classList.add('hidden');
  }
};

const resetModal = () => {
  elements.form?.reset();
  goToStep1();
  clearAssignee();

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
  if (!elements.form) return;

  if (isEditMode && editTaskId) {
    elements.form.setAttribute(
      'action',
      window.route('api.v1.admin.bpms.tasks.update', { task: editTaskId }),
    );
    elements.form.setAttribute('data-method', 'PUT');
  } else {
    elements.form.setAttribute(
      'action',
      window.route('api.v1.admin.bpms.tasks.store'),
    );
    elements.form.setAttribute('data-method', 'POST');
  }
};

const updateModalTexts = () => {
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
};

const populateFormWithTask = (taskData) => {
  if (!elements.form || !taskData) return;

  const titleInput = elements.form.querySelector('[name="title"]');
  const descriptionInput = elements.form.querySelector('[name="description"]');
  const priorityInput = elements.form.querySelector('[name="priority"]');
  const dueDateInput = elements.form.querySelector('[name="due_date"]');
  const estimatedHoursInput = elements.form.querySelector(
    '[name="estimated_hours"]',
  );

  if (titleInput) titleInput.value = taskData.title || '';
  if (descriptionInput) descriptionInput.value = taskData.description || '';
  if (priorityInput)
    priorityInput.value = taskData.priority?.value ?? taskData.priority ?? 1;
  if (dueDateInput) dueDateInput.value = taskData.due_date || '';
  if (estimatedHoursInput)
    estimatedHoursInput.value = taskData.estimated_hours || '';

  if (taskData.workflow_id) {
    selectedWorkflow = workflowsData.find((w) => w.id == taskData.workflow_id);
    if (selectedWorkflow) {
      const option = document.querySelector(
        `.workflow-option[data-workflow-id="${taskData.workflow_id}"]`,
      );
      if (option) {
        option
          .querySelector('div')
          ?.classList.add('border-indigo-600', 'bg-indigo-50');
        const input = option.querySelector('input');
        if (input) input.checked = true;
      }
      goToStep2();
    }
  }

  if (taskData.assignee_id && taskData.assignee) {
    selectAssignee(
      taskData.assignee_id,
      taskData.assignee.full_name || taskData.assignee.name || '',
      taskData.assignee.avatar?.file_url || '',
      false,
    );
  }
};

const openTaskModal = async (taskData = null) => {
  resetModal();

  if (taskData) {
    isEditMode = true;
    editTaskId = taskData.id;
    updateFormAction();
    updateModalTexts();
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

  document
    .getElementById('clearAssigneeBtn')
    ?.addEventListener('click', clearAssignee);

  elements.assigneeSearchInput?.addEventListener('focus', () => {
    elements.assigneeDropdown?.classList.remove('hidden');
  });

  elements.assigneeSearchInput?.addEventListener('input', function () {
    renderUserOptions(this.value);
    elements.assigneeDropdown?.classList.remove('hidden');
  });

  document.addEventListener('click', (e) => {
    if (
      !e.target.closest('#assigneeSearchInput') &&
      !e.target.closest('#assigneeDropdown')
    ) {
      elements.assigneeDropdown?.classList.add('hidden');
    }
  });

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
