# AJAX System - Advanced Topics

## Custom Actions

### Register Action

```javascript
import { registerAction } from './api/ajax-handler.js';

registerAction('actionName', (data, element) => {
  // data: response from server
  // element: HTML form or button element that triggered the action

  // Your logic here
});
```

### Example: Update Cart Count

```javascript
registerAction('updateCart', (data) => {
  const badge = document.querySelector('[data-cart-count]');
  badge.textContent = data.count;
});
```

### Example: Show Notification

```javascript
registerAction('showNotification', (data) => {
  showToast(data.message, 'success');
});
```

### Example: Multiple Operations

```javascript
registerAction('saveAndNotify', (data) => {
  // Update UI
  updateUserProfile(data.user);

  // Show message
  showToast('Profile updated');

  // Redirect after delay
  setTimeout(() => {
    window.location.href = '/profile';
  }, 1000);
});
```

---

## Event Listeners

Note: The declarative AJAX handler is auto-initialized via `resources/js/api/index.js`.
You generally do not need to call `initializeAjaxHandler()` manually.

Listen to form and button AJAX events for full control.

### Success Event (Form)

```javascript
const form = document.getElementById('myForm');

form.addEventListener('ajax:success', (event) => {
  const { response, form } = event.detail;
  console.log('Form submitted successfully:', response);
});
```

### Success Event (Button)

```javascript
const button = document.getElementById('myButton');

button.addEventListener('ajax:success', (event) => {
  const { response, button } = event.detail;
  console.log('Button clicked successfully:', response);
});
```

### Error Event (Form or Button)

```javascript
const element = document.getElementById('myElement');

element.addEventListener('ajax:error', (event) => {
  const { error, form, button } = event.detail;
  console.error('Request failed:', error);
});
```

### Example: Custom Handling

```javascript
const form = document.getElementById('userForm');

form.addEventListener('ajax:success', (event) => {
  const { response } = event.detail;

  if (response.requiresVerification) {
    // Custom logic
    showVerificationModal(response.email);
  } else {
    // Standard handling
    window.location.href = '/dashboard';
  }
});
```

---

## API Response Format

### Successful Response

```json
{
  "status": true,
  "message": "Operation successful",
  "result": {
    "id": 1,
    "name": "John"
  },
  "html": "<div class='item'>...</div>",
  "redirect_url": "/dashboard",
  "count": 5,
  "any_field": "value"
}
```

### Validation Error (422)

```json
{
  "status": false,
  "message": "Validation failed",
  "errors": {
    "email": ["Email is required"],
    "password": ["Password too short"]
  }
}
```

Errors display automatically next to fields.

### Server Error

```json
{
  "status": false,
  "message": "Internal server error"
}
```

---

## Advanced Selectors

### Target by Data Attribute

```html
<button
  data-ajax
  data-on-success="remove"
  data-target="[data-item-id='123']"
>Delete</button>
```

### Target by Class

```html
<form
  data-on-success="remove"
  data-target=".temporary-item"
>Delete All</form>
```

### Target Nested Element

```html
<form
  data-on-success="replace"
  data-target=".container .content"
>Update</form>
```

---

## Multiple Actions with Order

Actions execute in specified order:

```html
<!-- Prepend → Toggle → Reset -->
<form data-on-success="prepend toggle reset">...</form>

<!-- Append → Custom Action → Reload -->
<form
  data-on-success="append, custom, reload"
  data-action="myAction"
>...</form>
```

---

## Conditional Form Submission

```javascript
const form = document.getElementById('myForm');

form.addEventListener('submit', (event) => {
  if (someCondition) {
    event.preventDefault();
    // Handle differently
  }
});
```

---

## Manual Configuration Access

**Note:** Configuration parsing is handled internally by form and button handlers. For debugging purposes, you can access configuration through the handlers directly:

```javascript
import { getFormConfig } from './api/handlers/form-handler.js';
import { getButtonConfig } from './api/handlers/button-handler.js';

const form = document.getElementById('myForm');
const config = getFormConfig(form);

console.log(config);
// {
//   action: "...",
//   method: "POST",
//   actions: ["replace", "toggle"],
//   validate: true,
//   showMessage: true,
//   ...
// }
```

---

## Disable AJAX for Specific Form

```html
<!-- Standard form submission -->
<form action="/submit" method="POST">
  @csrf
  <!-- No data-ajax attribute -->
</form>
```

---

## Programmatic Submission

### Submit a Form

```javascript
const form = document.getElementById('myForm');

// Trigger form submission via AJAX
form.dispatchEvent(new Event('submit'));
```

### Trigger a Button

```javascript
const button = document.getElementById('myButton');

// Trigger button click via AJAX
button.dispatchEvent(new Event('click'));
```

---

## Skip Validation

```html
<form data-validate="false">
  <!-- Validation skipped -->
</form>
```

---

## Disable Success Messages

```html
<form data-show-message="false">
  <!-- No toast notifications -->
</form>
```

---

## Service Layer (For Advanced Use)

If you need more control:

```javascript
import { formService } from './services/form-service.js';
import { post } from './api/request.js';

// Manual validation
formService.validateForm(form);

// Manual submission
const result = await post(window.route('users.store'))
  .withData(data)
  .execute();
```

---

## Error Handling

### Server-side Validation

```php
// Controller
if ($fails = $this->validate($data)) {
    return response()->json([
        'status' => false,
        'message' => 'Validation failed',
        'errors' => $fails
    ], 422);
}
```

Errors display automatically next to fields.

### Custom Error Action

```html
<form data-on-error="show-modal" data-modal="#errorModal">
  <!-- Error modal shows on failure -->
</form>
```

---

## Debugging

### Check Configuration

```javascript
import { getFormConfig } from './api/handlers/form-handler.js';

const form = document.getElementById('myForm');
const config = getFormConfig(form);
console.log('Form config:', config);
```

### Check Registered Actions

```javascript
import { hasAction, getRegisteredActions } from './api/ajax-handler.js';

console.log('Action exists:', hasAction('myAction'));
console.log('All registered actions:', getRegisteredActions());
```

### Manual Action Execution

```javascript
import { executeAction } from './api/ajax-handler.js';

const form = document.getElementById('myForm');
executeAction('reload', {}, form);
```

---

## Performance Tips

### Debounce Search/Filter

```javascript
let timeout;
document.querySelector('#search').addEventListener('input', (e) => {
  clearTimeout(timeout);
  timeout = setTimeout(() => {
    form.dispatchEvent(new Event('submit'));
  }, 300);
});
```

### Prevent Double Submission

Already handled. Forms disabled during request.

### Lazy Load Data

```javascript
registerAction('loadMore', (data) => {
  if (data.hasMore) {
    loadNextPage();
  }
});
```
