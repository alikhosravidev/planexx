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

    const idInput = stateItem.querySelector('.state-id-input');
    const orderInput = stateItem.querySelector('.state-order-input');
    orderInput.value = statesContainer.children.length + 1;

    const colorInput = stateItem.querySelector('.state-color-input');
    const colorPaletteContainer = stateItem.querySelector(
      '.color-palette-container',
    );

    const nameInput = stateItem.querySelector('.state-name-input');
    const slugInput = stateItem.querySelector('.state-slug-input');
    const positionSelect = stateItem.querySelector('.state-position-select');
    const assigneeSelect = stateItem.querySelector(
      'select[name="states[][default_assignee_id]"]',
    );
    const descriptionTextarea = stateItem.querySelector(
      'textarea[name="states[][description]"]',
    );

    if (initialData) {
      if (initialData.name) nameInput.value = initialData.name;
      if (initialData.slug) {
        slugInput.value = initialData.slug;
      }
      if (initialData.color) {
        colorInput.value = initialData.color;
      }
      if (
        typeof initialData.position !== 'undefined' &&
        initialData.position !== null
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

      if (initialData.default_assignee_id) {
        assigneeSelect.value = initialData.default_assignee_id;
      }

      if (initialData.description) {
        descriptionTextarea.value = initialData.description.full;
      }

      if (initialData.id) {
        idInput.value = initialData.id;
      }

      if (initialData.order) {
        orderInput.value = initialData.order;
      }
    }

    const initialColor =
      initialData && initialData.color ? initialData.color : '#E3F2FD';
    renderColorPalette(colorPaletteContainer, colorInput, initialColor);

    nameInput.addEventListener('input', updatePreview);
    positionSelect.addEventListener('change', updatePreview);

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
      const orderInput = item.querySelector('.state-order-input');
      orderInput.value = index + 1;
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
  if (initialStatesRaw) {
    try {
      const initialStates = JSON.parse(initialStatesRaw);
      if (Array.isArray(initialStates) && initialStates.length > 0) {
        initialStates
          .sort((a, b) => (a.order || 0) - (b.order || 0))
          .forEach((state) => {
            addState({
              id: state.id ?? null,
              name: state.name ?? null,
              slug: state.slug ?? null,
              color: state.color ?? '#E3F2FD',
              position: state.position?.value ?? state.position ?? null,
              default_assignee_id: state.default_assignee_id ?? null,
              description: state.description?.full ?? null,
              order: state.order ?? null,
            });
          });
      }
    } catch (err) {
      console.error('Failed to parse initialStates', err);
    }
  }
}

document.addEventListener('DOMContentLoaded', initBpmsWorkflowPage, {
  once: true,
});
