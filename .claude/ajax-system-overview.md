# AJAX System - Overview (v2.0)

## What Is It?

Declarative AJAX system that requires **zero JavaScript** for standard forms. All configuration via HTML attributes.

**Version 2.0 Improvements:**
- ✅ **Secure**: HttpOnly cookie authentication, no client-side token storage
- ✅ **Modular**: Separated into focused modules (actions, handlers, utils)
- ✅ **Testable**: DI container for dependency injection and mocking
- ✅ **Maintainable**: Reduced from 626-line God Module to clean architecture
- ✅ **Validated**: Centralized validation rules
- ✅ **Clean**: Event management with automatic cleanup

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

## File Structure (v2.0)

```
resources/js/
├── api/
│   ├── actions/
│   │   ├── registry.js       # Custom action registration
│   │   ├── built-in.js       # 14 built-in actions
│   │   ├── executor.js       # Action execution logic
│   │   └── index.js          # Central export
│   ├── handlers/
│   │   ├── form-handler.js   # Form submission logic
│   │   ├── button-handler.js # Button click logic
│   │   └── index.js          # Central export
│   ├── ajax-handler.js       # Orchestrator (60 lines)
│   ├── http-client.js        # Axios with Sanctum CSRF
│   └── request.js            # Request builder
├── utils/
│   ├── di-container.js       # Dependency injection
│   ├── dom.js                # DOM manipulation helpers
│   └── event-manager.js      # Event cleanup tracking
├── services/
│   └── form-service.js       # Form validation & submission
├── auth/
│   ├── actions.js            # Auth custom actions
│   ├── otp.js                # OTP inputs handling
│   └── ui.js                 # Auth UI helpers
├── bootstrap-di.js           # DI container setup
└── bootstrap.js              # Application initialization
```

## Key Features

### Core Features
- ✅ Zero JavaScript for standard forms
- ✅ Automatic validation with centralized rules
- ✅ Automatic error handling
- ✅ 14 built-in actions
- ✅ Custom action registry
- ✅ Ziggy route integration (`window.route()`)
- ✅ CSRF protection (Sanctum)
- ✅ Loading state support
- ✅ Multiple actions support

### v2.0 Enhancements
- ✅ **Security**: HttpOnly cookie auth, no client-side token storage
- ✅ **Testability**: DI container for mocking dependencies
- ✅ **Modularity**: Separated concerns (SRP, OCP)
- ✅ **DOM Utilities**: Centralized DOM manipulation
- ✅ **Event Management**: Automatic cleanup tracking
- ✅ **Validation**: Single source of truth for rules

## Entrypoints (Single Responsibility)

- `resources/js/bootstrap-di.js` – Registers services in DI container (first)
- `resources/js/api/index.js` – Initializes the declarative AJAX handler
- `resources/js/forms/index.js` – Initializes generic form utilities
- `resources/js/auth/index.js` – Registers auth custom actions

Bootstrap wires these in order:

```javascript
// resources/js/bootstrap.js
import './bootstrap-di.js';  // DI container first
import './api/index.js';
import './forms/index.js';
import './auth/index.js';
```

## Auth Flow (v2.0 - Secure)

### Token Management
- ✅ **Server-side only**: Token is set by backend in HttpOnly cookie
- ✅ **No client storage**: JavaScript never touches the token
- ✅ **Automatic**: Browser sends cookie with every request
- ❌ **Never use**: `cookieUtils.set('token', ...)` (removed in v2.0)

### Login Flow
- Server sets HttpOnly cookie and returns `redirect_url` in response
- Built-in `redirect` action handles navigation to dashboard
- Custom actions like `show-otp-step` and `resend-success` handle multi-step flows
- If OTP is invalid, no redirect occurs; user stays on OTP step

## Ziggy Route Usage (Important)

**Always use `window.route()` in JavaScript:**

```javascript
// ✅ Correct
const url = window.route('users.store');
const dashboardUrl = window.route('dashboard');

// ❌ Wrong - will cause ReferenceError
const url = route('users.store');
```

- Ziggy is loaded via `@routes` directive in Blade templates
- Makes `route` function available on `window` object
- In Blade templates: use Laravel's `route()` as usual
- In JavaScript: always use `window.route()`

## When to Use

- **New forms:** Always use this system
- **AJAX requests:** Use `data-ajax` attribute
- **Filters/Search:** Use `replace` action
- **CRUD operations:** Use appropriate actions
- **Complex logic:** Register custom actions
- **Testing:** Use DI container to mock dependencies

## Testing Support (v2.0)

```javascript
// Mock dependencies for testing
import { container } from '@/utils/di-container.js';

container.override('httpClient', mockHttpClient);
container.override('notifications', mockNotifications);

// Your test code here

// Cleanup
container.clearSingletons();
```

## Next Steps

1. Read: `.claude/ajax/attributes.md` (All available attributes)
2. Read: `.claude/ajax/actions.md` (All 14 actions)
3. See: `.claude/ajax/examples.md` (Real-world examples)
4. Advanced: `.claude/ajax/advanced.md` (Custom actions, events)
