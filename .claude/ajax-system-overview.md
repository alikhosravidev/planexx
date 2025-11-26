# AJAX System - Overview

## What Is It?

Declarative AJAX system that requires **zero JavaScript** for standard forms. All configuration via HTML attributes.

## Core Concept

### Forms

```html
<form
  data-ajax
  action="{{ route('users.store') }}"
  method="POST"
  data-on-success="prepend"
  data-target="#users-list"
>
  @csrf
  <input type="text" name="name" required />
  <button>Save</button>
</form>
```

### Buttons

```html
<button
  data-ajax
  action="{{ route('users.destroy', $user->id) }}"
  data-method="DELETE"
  data-on-success="remove"
  data-target="[data-user-id='{{ $user->id }}']"
>
  Delete
</button>
```

No JavaScript needed. Just HTML attributes.

## How It Works

### For Forms
1. Form submitted with `data-ajax` attribute
2. AJAX handler intercepts submission
3. Validates form fields (optional)
4. Sends HTTP request with form data
5. Executes response action (reload, redirect, replace, etc.)
6. Optionally dispatches custom events

### For Buttons
1. Button clicked with `data-ajax` attribute
2. AJAX handler intercepts click
3. Sends HTTP request (no form data)
4. Executes response action
5. Optionally dispatches custom events

## 3 Usage Levels

### Level 1: Built-in Actions (Zero JS)
Use one of 14 built-in actions. No custom code needed.

**Forms:**
```html
<form data-ajax data-on-success="reload">...</form>
<form data-ajax data-on-success="redirect">...</form>
<form data-ajax data-on-success="prepend" data-target="#list">...</form>
```

**Buttons:**
```html
<button data-ajax data-on-success="remove" data-target="#item">Delete</button>
<button data-ajax data-on-success="toggle" data-target="#details">Toggle</button>
```

### Level 2: Custom Actions (Minimal JS)
Register custom action once, use everywhere.

```javascript
// app.js - one time
registerAction('updateCart', (data) => {
  document.querySelector('[data-count]').textContent = data.count;
});
```

```html
<form data-on-success="custom" data-action="updateCart">...</form>
```

### Level 3: Event Listeners (Advanced)
Full control via event listeners.

```javascript
form.addEventListener('ajax:success', (event) => {
  const { response, form } = event.detail;
  // custom handling
});
```

## File Structure

```
resources/js/
├── api/
│   ├── ajax-handler.js       # Core AJAX handler
│   ├── http-client.js        # Axios configuration
│   └── request.js            # Request builder (for advanced use)
├── auth/
│   ├── actions.js            # Auth custom actions (login-success, etc.)
│   ├── otp.js                # OTP inputs handling
│   └── ui.js                 # Auth UI helpers (back button, step switch)
├── services/
│   └── form-service.js       # Form validation & submission
└── bootstrap.js              # Initialization
```

## Key Features

- ✅ Zero JavaScript for standard forms
- ✅ Automatic validation
- ✅ Automatic error handling
- ✅ 14 built-in actions
- ✅ Custom action registry
- ✅ Ziggy route integration
- ✅ CSRF protection
- ✅ Token auto-injection
- ✅ Loading state support
- ✅ Multiple actions support

## Entrypoints (Single Responsibility)

- `resources/js/api/index.js` – Initializes the declarative AJAX handler once on page load (no manual calls needed).
- `resources/js/forms/index.js` – Initializes generic form utilities (e.g., search).
- `resources/js/auth/index.js` – Registers auth custom actions and auto-initializes OTP/UI behavior.

Bootstrap only wires these entrypoints:

```javascript
// resources/js/bootstrap.js
import './api/index.js';
import './forms/index.js';
import './auth/index.js';
```

## Auth Flow Note

- `login-success` action redirects only when a valid token is present in the response (e.g., `data.auth` or `data.auth.token`).
- If OTP is invalid or token is missing, no redirect occurs; the user stays on the OTP step and sees the error.

## Ziggy Route Usage (Important)

When generating URLs in JavaScript (custom actions, event listeners, or manual API calls), always use Ziggy with an explicit import:

```javascript
import { route } from 'ziggy-js';

const url = route('users.store');
```

- In Blade templates (HTML attributes like `action="{{ route('...') }}"`) use Laravel's `route()` as usual.
- In JavaScript files, do not hardcode paths; use `route()` from Ziggy to keep URLs in sync with backend routes.

## When to Use

- **New forms:** Always use this system
- **AJAX requests:** Use `data-ajax` attribute
- **Filters/Search:** Use `replace` action
- **CRUD operations:** Use appropriate actions
- **Complex logic:** Register custom actions

## Next Steps

1. Read: `.claude/ajax/attributes.md` (All available attributes)
2. Read: `.claude/ajax/actions.md` (All 14 actions)
3. See: `.claude/ajax/examples.md` (Real-world examples)
4. Advanced: `.claude/ajax/advanced.md` (Custom actions, events)
