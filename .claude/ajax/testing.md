# AJAX System - Testing Guide

## Overview

The AJAX system v2.0 is designed with testability in mind. All dependencies are injected via a DI container, making it easy to mock and test individual components.

## Dependency Injection Container

### Basic Usage

```javascript
import { container, resolve, register } from '@/utils/di-container.js';

// Get a service
const httpClient = resolve('httpClient');
const notifications = resolve('notifications');

// Register a new service
register('myService', () => new MyService(), true);
```

### Mocking for Tests

```javascript
import { container } from '@/utils/di-container.js';

// Mock httpClient
const mockHttpClient = {
  request: jest.fn().mockResolvedValue({ data: { success: true } })
};

container.override('httpClient', mockHttpClient);

// Run your tests
// ...

// Cleanup
container.clearSingletons();
```

## Testing Form Handler

```javascript
import { handleFormSubmit } from '@/api/handlers/form-handler.js';
import { container } from '@/utils/di-container.js';

describe('Form Handler', () => {
  let mockFormService;
  let mockNotifications;

  beforeEach(() => {
    // Setup mocks
    mockFormService = {
      validateForm: jest.fn().mockReturnValue(true),
      submitForm: jest.fn().mockResolvedValue({ success: true }),
      displayValidationErrors: jest.fn()
    };

    mockNotifications = {
      showSuccess: jest.fn(),
      showError: jest.fn()
    };

    // Override dependencies
    container.override('formService', mockFormService);
    container.override('notifications', mockNotifications);
  });

  afterEach(() => {
    container.clearSingletons();
  });

  test('should submit form successfully', async () => {
    const form = document.createElement('form');
    form.setAttribute('data-ajax', '');
    form.setAttribute('action', '/test');
    
    const event = new Event('submit');
    Object.defineProperty(event, 'target', { value: form });

    await handleFormSubmit(event);

    expect(mockFormService.validateForm).toHaveBeenCalledWith(form);
    expect(mockFormService.submitForm).toHaveBeenCalled();
  });
});
```

## Testing Button Handler

```javascript
import { handleButtonClick } from '@/api/handlers/button-handler.js';
import { container } from '@/utils/di-container.js';

describe('Button Handler', () => {
  let mockHttpClient;
  let mockNotifications;

  beforeEach(() => {
    mockHttpClient = {
      request: jest.fn().mockResolvedValue({ 
        data: { success: true, message: 'Done' } 
      })
    };

    mockNotifications = {
      showSuccess: jest.fn(),
      showError: jest.fn()
    };

    container.override('httpClient', mockHttpClient);
    container.override('notifications', mockNotifications);
  });

  afterEach(() => {
    container.clearSingletons();
  });

  test('should handle button click', async () => {
    const button = document.createElement('button');
    button.setAttribute('data-ajax', '');
    button.setAttribute('data-action', '/test');

    const event = {
      currentTarget: button,
      preventDefault: jest.fn()
    };

    await handleButtonClick(event);

    expect(mockHttpClient.request).toHaveBeenCalledWith({
      method: 'POST',
      url: '/test',
      data: {},
      params: undefined
    });
    expect(mockNotifications.showSuccess).toHaveBeenCalledWith('Done');
  });
});
```

## Testing Custom Actions

```javascript
import { registerAction, executeAction } from '@/api/actions/index.js';

describe('Custom Actions', () => {
  test('should register and execute custom action', () => {
    const mockHandler = jest.fn();
    
    registerAction('testAction', mockHandler);
    
    const data = { test: 'data' };
    const element = document.createElement('div');
    
    executeAction('testAction', data, element);
    
    expect(mockHandler).toHaveBeenCalledWith(data, element);
  });
});
```

## Testing DOM Utilities

```javascript
import { addClasses, removeClasses, createElement } from '@/utils/dom.js';

describe('DOM Utilities', () => {
  test('should add classes to element', () => {
    const element = document.createElement('div');
    
    addClasses(element, 'class1 class2');
    
    expect(element.classList.contains('class1')).toBe(true);
    expect(element.classList.contains('class2')).toBe(true);
  });

  test('should create element with attributes', () => {
    const element = createElement('div', { 
      class: 'test-class',
      id: 'test-id' 
    }, 'Test content');
    
    expect(element.tagName).toBe('DIV');
    expect(element.classList.contains('test-class')).toBe(true);
    expect(element.id).toBe('test-id');
    expect(element.textContent).toBe('Test content');
  });
});
```

## Testing Event Manager

```javascript
import { eventManager } from '@/utils/event-manager.js';

describe('Event Manager', () => {
  let element;

  beforeEach(() => {
    element = document.createElement('div');
    document.body.appendChild(element);
  });

  afterEach(() => {
    eventManager.offAll();
    document.body.removeChild(element);
  });

  test('should add and remove event listener', () => {
    const handler = jest.fn();
    
    const listenerId = eventManager.on(element, 'click', handler);
    
    element.click();
    expect(handler).toHaveBeenCalledTimes(1);
    
    eventManager.off(listenerId);
    
    element.click();
    expect(handler).toHaveBeenCalledTimes(1); // Still 1, not called again
  });

  test('should cleanup all listeners', () => {
    const handler1 = jest.fn();
    const handler2 = jest.fn();
    
    eventManager.on(element, 'click', handler1);
    eventManager.on(element, 'mouseover', handler2);
    
    expect(eventManager.getListenerCount()).toBe(2);
    
    eventManager.offAll();
    
    expect(eventManager.getListenerCount()).toBe(0);
  });
});
```

## Integration Testing

```javascript
import { initializeAjaxHandler } from '@/api/ajax-handler.js';
import { container } from '@/utils/di-container.js';

describe('AJAX System Integration', () => {
  let mockHttpClient;

  beforeEach(() => {
    mockHttpClient = {
      request: jest.fn().mockResolvedValue({ 
        data: { success: true, html: '<div>New content</div>' } 
      })
    };

    container.override('httpClient', mockHttpClient);
    
    initializeAjaxHandler();
  });

  afterEach(() => {
    container.clearSingletons();
  });

  test('should handle form submission end-to-end', async () => {
    const form = document.createElement('form');
    form.setAttribute('data-ajax', '');
    form.setAttribute('action', '/test');
    form.setAttribute('data-on-success', 'reload');
    
    document.body.appendChild(form);
    
    const submitEvent = new Event('submit', { bubbles: true });
    form.dispatchEvent(submitEvent);
    
    // Wait for async operations
    await new Promise(resolve => setTimeout(resolve, 100));
    
    expect(mockHttpClient.request).toHaveBeenCalled();
    
    document.body.removeChild(form);
  });
});
```

## Best Practices

1. **Always cleanup**: Use `afterEach` to clear singletons and remove DOM elements
2. **Mock dependencies**: Override services in DI container, don't import directly
3. **Test isolation**: Each test should be independent
4. **Use factories**: Create helper functions for common test setups
5. **Test behavior**: Focus on what the code does, not how it does it

## Running Tests

```bash
# Run all tests
npm test

# Run specific test file
npm test -- ajax-handler.test.js

# Run with coverage
npm test -- --coverage

# Watch mode
npm test -- --watch
```
