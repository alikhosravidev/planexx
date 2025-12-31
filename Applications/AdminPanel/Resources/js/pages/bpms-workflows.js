import { initTomSelect } from '@shared-js/tom-select/index.js';

const stateColors = [
  '#E3F2FD',
  '#BBDEFB',
  '#E8F5E9',
  '#C8E6C9',
  '#FFF3E0',
  '#FFE0B2',
  '#FFF8E1',
  '#FFECB3',
  '#F3E5F5',
  '#E1BEE7',
  '#E0F2F1',
  '#B2DFDB',
  '#FFEBEE',
  '#FFCDD2',
  '#FCE4EC',
  '#F8BBD9',
  '#E8EAF6',
  '#C5CAE9',
  '#ECEFF1',
  '#CFD8DC',
];

function initBpmsWorkflowPage() {
  const form = document.querySelector('form[data-ajax]');
  if (!form) {
    return;
  }

  let stateCounter = 0;
  let activeStateElement = null;

  const statesContainer = document.getElementById('statesContainer');
  const emptyMessage = document.getElementById('emptyStatesMessage');
  const statesPreview = document.getElementById('statesPreview');
  const previewContainer = document.getElementById('previewContainer');
  const stateTemplate = document.getElementById('stateTemplate');
  const addStateBtns = document.querySelectorAll('.addStateBtn');

  if (
    !statesContainer ||
    !emptyMessage ||
    !statesPreview ||
    !previewContainer ||
    !stateTemplate ||
    !addStateBtns ||
    addStateBtns.length === 0
  ) {
    return;
  }

  addStateBtns.forEach(function (elm) {
    elm.addEventListener('click', function () {
      addState();
    });
  });

  function renderColorPalette(
    container,
    hiddenInput,
    selectedColor = '#E3F2FD',
  ) {
    container.innerHTML = '';

    if (hiddenInput) {
      container.appendChild(hiddenInput);
    }

    stateColors.forEach((color) => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className =
        'color-square w-6 h-6 rounded flex-shrink-0 border-2 transition-all duration-150 hover:scale-110 ' +
        (color === selectedColor
          ? 'border-indigo-600 ring-2 ring-indigo-200'
          : 'border-transparent hover:border-gray-300');
      btn.style.backgroundColor = color;
      btn.dataset.color = color;
      btn.title = color;

      btn.addEventListener('click', () => {
        hiddenInput.value = color;
        container.querySelectorAll('.color-square').forEach((el) => {
          el.classList.remove('border-indigo-600', 'ring-2', 'ring-indigo-200');
          el.classList.add('border-transparent');
        });
        btn.classList.remove('border-transparent');
        btn.classList.add('border-indigo-600', 'ring-2', 'ring-indigo-200');
        updatePreview();
      });

      container.appendChild(btn);
    });

    hiddenInput.value = selectedColor;
  }

  function addState(initialData = null) {
    stateCounter++;

    const clone = stateTemplate.content.cloneNode(true);
    const stateItem = clone.querySelector('.state-item');
    stateItem.dataset.stateId = String(stateCounter);

    const currentIndex = statesContainer.children.length;

    const idInput = stateItem.querySelector('.state-id-input');
    const orderInput = stateItem.querySelector('.state-order-input');
    orderInput.value = currentIndex + 1;

    const colorInput = stateItem.querySelector('.state-color-input');
    const colorPaletteContainer = stateItem.querySelector(
      '.color-palette-container',
    );

    // Update form field names with proper indexing
    const nameInput = stateItem.querySelector('.state-name-input');
    nameInput.name = `states[${currentIndex}][name]`;

    const slugInput = stateItem.querySelector('.state-slug-input');
    slugInput.name = `states[${currentIndex}][slug]`;

    colorInput.name = `states[${currentIndex}][color]`;

    const positionSelect = stateItem.querySelector('.state-position-select');
    positionSelect.name = `states[${currentIndex}][position]`;

    const assigneeSelect = stateItem.querySelector(
      'select[name="states[][default_assignee_id]"]',
    );
    if (assigneeSelect) {
      assigneeSelect.name = `states[${currentIndex}][default_assignee_id]`;
    }

    const descriptionTextarea = stateItem.querySelector(
      'textarea[name="states[][description]"]',
    );
    descriptionTextarea.name = `states[${currentIndex}][description]`;

    // Update allowed_roles field name
    // The tom-select-ajax component creates the select with name="states[][allowed_roles][]"
    const allowedRolesSelect = stateItem.querySelector(
      'select[data-tom-select-ajax]',
    );
    console.log('🔍 DEBUG: Found allowedRolesSelect:', {
      found: !!allowedRolesSelect,
      originalName: allowedRolesSelect?.name,
      newName: `states[${currentIndex}][allowed_roles][]`,
      hasDataAttribute: !!allowedRolesSelect?.hasAttribute('data-tom-select-ajax')
    });

    if (allowedRolesSelect) {
      allowedRolesSelect.name = `states[${currentIndex}][allowed_roles][]`;
      console.log('🔍 DEBUG: Updated allowedRolesSelect name to:', allowedRolesSelect.name);
    }

    // Update hidden fields
    idInput.name = `states[${currentIndex}][id]`;
    orderInput.name = `states[${currentIndex}][order]`;

    if (initialData) {
      if (initialData.name && nameInput) nameInput.value = initialData.name;
      if (initialData.slug && slugInput) {
        slugInput.value = initialData.slug;
      }
      if (initialData.color && colorInput) {
        colorInput.value = initialData.color;
      }
      if (
        typeof initialData.position !== 'undefined' &&
        initialData.position !== null &&
        positionSelect
      ) {
        let posValue = initialData.position;
        if (typeof posValue === 'object' && posValue.value !== undefined) {
          posValue = posValue.value;
        }

        switch (String(posValue)) {
          case '0':
          case 'start':
            positionSelect.value = 'start';
            break;
          case '2':
          case 'final_success':
          case 'final-success':
            positionSelect.value = 'final-success';
            break;
          case '3':
          case 'final_failed':
          case 'final-failed':
            positionSelect.value = 'final-failed';
            break;
          case '4':
          case 'final_closed':
          case 'final-closed':
            positionSelect.value = 'final-closed';
            break;
          default:
            positionSelect.value = 'middle';
        }
      }

      if (initialData.default_assignee_id && assigneeSelect) {
        assigneeSelect.value = initialData.default_assignee_id;
      }

      if (initialData.description && descriptionTextarea) {
        descriptionTextarea.value = initialData.description.full;
      }

      if (initialData.id && idInput) {
        idInput.value = initialData.id;
      }

      if (initialData.order && orderInput) {
        orderInput.value = initialData.order;
      }

      // Set allowed_roles for the state
      console.log('🔍 DEBUG: Setting allowed_roles for state:', {
        stateId: initialData.id,
        stateName: initialData.name,
        allowed_roles: initialData.allowed_roles,
        allowedRolesSelectFound: !!allowedRolesSelect
      });

      if (initialData.allowed_roles && allowedRolesSelect) {
        console.log('🔍 DEBUG: Storing initial values in dataset:', initialData.allowed_roles);
        allowedRolesSelect.dataset.initialValues = JSON.stringify(initialData.allowed_roles);
      } else {
        console.log('🔍 DEBUG: No allowed_roles data or select element not found');
      }
    }

    const initialColor =
      initialData && initialData.color ? initialData.color : '#E3F2FD';
    renderColorPalette(colorPaletteContainer, colorInput, initialColor);

    if (nameInput) nameInput.addEventListener('input', updatePreview);
    if (positionSelect) positionSelect.addEventListener('change', updatePreview);

    const deleteBtn = stateItem.querySelector('.delete-state-btn');
    deleteBtn.addEventListener('click', function () {
      stateItem.remove();
      updateStatesUI();
      updatePreview();
    });

    stateItem.addEventListener('click', function (e) {
      if (
        e.target.closest('.delete-state-btn') ||
        e.target.closest('.state-drag-handle')
      )
        return;
      setActiveState(this);
    });

    statesContainer.appendChild(clone);

  // Initialize tom-select for the new state item
  console.log('🔍 DEBUG: Initializing TomSelect for state item');
  if (typeof initTomSelect === 'function') {
    console.log('🔍 DEBUG: Using initTomSelect function');
    initTomSelect(stateItem);
  } else if (window.tomSelectService) {
    console.log('🔍 DEBUG: Using window.tomSelectService');
    window.tomSelectService.init(stateItem);
  } else {
    console.warn('⚠️ No TomSelect initialization method found');
  }
  console.log('🔍 DEBUG: TomSelect initialization completed');

  // Set initial values for allowed_roles TomSelect after initialization
  // Find the select element after name updates and TomSelect initialization
  const finalAllowedRolesSelect = stateItem.querySelector('select[data-tom-select-ajax]');
  console.log('🔍 DEBUG: Final allowed roles select element:', {
    found: !!finalAllowedRolesSelect,
    name: finalAllowedRolesSelect?.name,
    hasInitialValues: !!finalAllowedRolesSelect?.dataset.initialValues,
    initialValues: finalAllowedRolesSelect?.dataset.initialValues
  });

  if (finalAllowedRolesSelect && finalAllowedRolesSelect.dataset.initialValues) {
    try {
      const initialValues = JSON.parse(finalAllowedRolesSelect.dataset.initialValues);
      console.log('🔍 DEBUG: Parsed initial values for TomSelect:', initialValues);

      if (Array.isArray(initialValues) && initialValues.length > 0) {
        console.log('🔍 DEBUG: Setting timeout to initialize TomSelect values');
        // Wait for TomSelect to be fully initialized
        setTimeout(() => {
          console.log('🔍 DEBUG: Timeout executed, checking TomSelect instance');
          const tomSelectInstance = finalAllowedRolesSelect.tomselect;
          console.log('🔍 DEBUG: TomSelect instance:', {
            found: !!tomSelectInstance,
            type: typeof tomSelectInstance
          });

          if (tomSelectInstance) {
            console.log('🔍 DEBUG: Processing initial values for TomSelect:', initialValues);

            // For TomSelect Ajax, we need to add options before setting values
            initialValues.forEach(role => {
              const option = {
                id: role.id,
                label: role.title || role.name,
                value: role.id,
                text: role.title || role.name
              };
              console.log('🔍 DEBUG: Adding option to TomSelect:', option);
              tomSelectInstance.addOption(option);
            });

            // Convert role objects to IDs
            const roleIds = initialValues.map(role => role.id);
            console.log('🔍 DEBUG: Setting TomSelect values:', roleIds);

            // Check current values before setting
            console.log('🔍 DEBUG: Current TomSelect values before setValue:', tomSelectInstance.getValue());
            console.log('🔍 DEBUG: Available options before setValue:', Object.keys(tomSelectInstance.options));

            tomSelectInstance.setValue(roleIds);

            // Check values after setting
            setTimeout(() => {
              console.log('🔍 DEBUG: TomSelect values after setValue:', tomSelectInstance.getValue());
              console.log('🔍 DEBUG: TomSelect items count:', tomSelectInstance.items.length);
              console.log('🔍 DEBUG: Available options after setValue:', Object.keys(tomSelectInstance.options));
            }, 100);

            console.log('✅ TomSelect values set successfully');
          } else {
            console.warn('⚠️ TomSelect instance not found for allowed_roles');
          }
        }, 300);
      } else {
        console.log('🔍 DEBUG: No initial values to set (empty or not array)');
      }
    } catch (err) {
      console.error('❌ Failed to parse initial allowed_roles values', err);
    }
  } else {
    console.log('🔍 DEBUG: No final select element or initial values found');
  }

  updateStatesUI();
  setActiveState(stateItem);

    if (!initialData) {
      setTimeout(() => nameInput.focus(), 100);
    }
  }

  function setActiveState(element) {
    document.querySelectorAll('.state-item').forEach((item) => {
      item.classList.remove('ring-2', 'ring-indigo-500');
    });
    if (element) {
      element.classList.add('ring-2', 'ring-indigo-500');
      activeStateElement = element;
    }
  }

  function updateStatesUI() {
    const hasStates = statesContainer.children.length > 0;
    emptyMessage.classList.toggle('hidden', hasStates);
    statesPreview.classList.toggle('hidden', !hasStates);

    Array.from(statesContainer.children).forEach((item, index) => {
      // Update order
      const orderInput = item.querySelector('.state-order-input');
      orderInput.value = index + 1;

      // Re-index all form fields to maintain proper association
      const nameInput = item.querySelector('input[name^="states["]');
      if (nameInput && nameInput.name.includes('[name]')) {
        nameInput.name = `states[${index}][name]`;
      }

      const slugInput = item.querySelector('input[name^="states["][name*="[slug]"]');
      if (slugInput) {
        slugInput.name = `states[${index}][slug]`;
      }

      const colorInput = item.querySelector('input[name^="states["][name*="[color]"]');
      if (colorInput) {
        colorInput.name = `states[${index}][color]`;
      }

      const positionSelect = item.querySelector('select[name^="states["][name*="[position]"]');
      if (positionSelect) {
        positionSelect.name = `states[${index}][position]`;
      }

      const assigneeSelect = item.querySelector('select[name^="states["][name*="[default_assignee_id]"]');
      if (assigneeSelect) {
        assigneeSelect.name = `states[${index}][default_assignee_id]`;
      }

      const descriptionTextarea = item.querySelector('textarea[name^="states["][name*="[description]"]');
      if (descriptionTextarea) {
        descriptionTextarea.name = `states[${index}][description]`;
      }

      const allowedRolesSelect = item.querySelector('select[name^="states["][name*="[allowed_roles]"]');
      if (allowedRolesSelect) {
        allowedRolesSelect.name = `states[${index}][allowed_roles][]`;
      }

      const idInput = item.querySelector('input[name^="states["][name*="[id]"]');
      if (idInput) {
        idInput.name = `states[${index}][id]`;
      }

      orderInput.name = `states[${index}][order]`;
    });

    updatePreview();
  }

  function updatePreview() {
    previewContainer.innerHTML = '';

    const states = Array.from(statesContainer.querySelectorAll('.state-item'));

    const emptyPreview = document.getElementById('emptyPreview');
    const statesCountBadge = document.getElementById('statesCountBadge');

    if (statesCountBadge) {
      statesCountBadge.textContent = states.length + ' مرحله';
    }

    if (emptyPreview) {
      emptyPreview.classList.toggle('hidden', states.length > 0);
    }

    states.forEach((state, index) => {
      const nameInput = state.querySelector('.state-name-input');
      const colorInput = state.querySelector('.state-color-input');
      const positionSelect = state.querySelector('.state-position-select');

      const name = nameInput?.value || `مرحله ${index + 1}`;
      const color = colorInput?.value || '#E3F2FD';
      const position = positionSelect?.value || 'middle';

      const isFinal = position.startsWith('final');
      const isStart = position === 'start';
      const displayIndex = (index + 1).toLocaleString('fa-IR');
      const statusBadge = isStart
        ? '<span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-medium bg-white/60 text-green-700"><i class="fa-solid fa-play text-[7px]"></i>شروع</span>'
        : isFinal
          ? '<span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-medium bg-white/60 text-gray-700"><i class="fa-solid fa-flag-checkered text-[7px]"></i>پایان</span>'
          : '';

      const stateBox = document.createElement('div');
      stateBox.className = 'relative rounded-lg overflow-hidden mb-1';
      stateBox.innerHTML = `
        <div class="relative px-2.5 py-2 flex items-center gap-2" style="background-color: ${color}">
          <span class="w-6 h-6 flex items-center justify-center text-sm font-bold bg-white/70 text-gray-700 rounded-md flex-shrink-0 shadow-sm">${displayIndex}</span>
          <div class="flex-1 text-sm font-medium text-gray-800 truncate">${name}</div>
          ${statusBadge}
        </div>
      `;

      previewContainer.appendChild(stateBox);
    });
  }

  let draggedItem = null;

  statesContainer.addEventListener('dragstart', function (e) {
    if (e.target.classList.contains('state-item')) {
      draggedItem = e.target;
      e.target.style.opacity = '0.5';
    }
  });

  statesContainer.addEventListener('dragend', function (e) {
    if (e.target.classList.contains('state-item')) {
      e.target.style.opacity = '1';
      draggedItem = null;
      updateStatesUI();
    }
  });

  statesContainer.addEventListener('dragover', function (e) {
    e.preventDefault();
  });

  statesContainer.addEventListener('drop', function (e) {
    e.preventDefault();
    const target = e.target.closest('.state-item');
    if (target && draggedItem && target !== draggedItem) {
      const allItems = [...statesContainer.querySelectorAll('.state-item')];
      const draggedIndex = allItems.indexOf(draggedItem);
      const targetIndex = allItems.indexOf(target);

      if (draggedIndex < targetIndex) {
        target.after(draggedItem);
      } else {
        target.before(draggedItem);
      }
    }
  });

  const observer = new MutationObserver(function () {
    statesContainer.querySelectorAll('.state-item').forEach((item) => {
      item.draggable = true;
    });
  });
  observer.observe(statesContainer, { childList: true });

  const initialStatesRaw = form.dataset.initialStates;
  console.log('🔍 DEBUG: initialStatesRaw:', initialStatesRaw);

  if (initialStatesRaw) {
    try {
      const initialStates = JSON.parse(initialStatesRaw);
      console.log('🔍 DEBUG: Parsed initialStates:', initialStates);

      if (Array.isArray(initialStates) && initialStates.length > 0) {
        initialStates
          .sort((a, b) => (a.order || 0) - (b.order || 0))
          .forEach((state, index) => {
            console.log(`🔍 DEBUG: Processing state ${index}:`, {
              id: state.id,
              name: state.name,
              allowed_roles: state.allowed_roles,
              allowedRoles: state.allowedRoles
            });

            addState({
              id: state.id ?? null,
              name: state.name ?? null,
              slug: state.slug ?? null,
              color: state.color ?? '#E3F2FD',
              position: state.position?.value ?? state.position ?? null,
              default_assignee_id: state.default_assignee_id ?? null,
              description: state.description?.full ?? null,
              order: state.order ?? null,
              allowed_roles: state.allowed_roles ?? state.allowedRoles ?? null,
            });
          });
      } else {
        console.log('🔍 DEBUG: No initial states found or empty array');
      }
    } catch (err) {
      console.error('❌ Failed to parse initialStates', err);
    }
  } else {
    console.log('🔍 DEBUG: No initialStatesRaw data found');
  }
}

document.addEventListener('DOMContentLoaded', initBpmsWorkflowPage, {
  once: true,
});
