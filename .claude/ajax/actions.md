# AJAX Actions Reference

14 built-in actions for response handling.

## Navigation Actions

### `reload`
Reload current page.

```html
<form data-on-success="reload">...</form>
```

### `redirect`
Redirect to URL. API must return `redirect_url`.

```html
<form data-on-success="redirect">...</form>
```

**API Response:**
```json
{
  "status": true,
  "redirect_url": "/dashboard"
}
```

### `back`
Go back in browser history.

```html
<form data-on-success="back">...</form>
```

---

## DOM Manipulation Actions

### `replace`
Replace element content. Requires `data-target` and API to return `html`.

```html
<form
  data-on-success="replace"
  data-target="#products"
>...</form>

<div id="products"><!-- Replaced --></div>
```

**API Response:**
```json
{
  "html": "<div class='product'>...</div>"
}
```

### `append`
Append HTML to element.

```html
<form
  data-on-success="append"
  data-target="#comments"
>...</form>

<div id="comments">
  <!-- New comments appended -->
</div>
```

### `prepend`
Prepend HTML to element. Best for newest items first.

```html
<form
  data-on-success="prepend"
  data-target="#messages"
>...</form>

<div id="messages">
  <!-- Newest messages at top -->
</div>
```

### `remove`
Remove element from DOM.

```html
<button
  data-ajax
  action="{{ route('items.destroy', $item->id) }}"
  data-on-success="remove"
  data-target="[data-id='{{ $item->id }}']"
>Delete</button>
```

### `toggle`
Toggle element visibility (add/remove `hidden` class).

```html
<form
  data-on-success="toggle"
  data-target="#details"
>...</form>
```

---

## CSS Class Actions

### `add-class`
Add CSS classes. Requires `data-class`.

```html
<form
  data-on-success="add-class"
  data-target="#message"
  data-class="success text-green"
>...</form>
```

### `remove-class`
Remove CSS classes. Requires `data-class`.

```html
<form
  data-on-success="remove-class"
  data-target=".item"
  data-class="disabled"
>...</form>
```

---

## Form Actions

### `reset`
Clear form fields after submission.

```html
<form data-on-success="reset">
  <input name="title" />
  <button>Save</button>
</form>
```

---

## Modal Actions

### `show-modal`
Show modal. Requires `data-modal`.

```html
<form
  data-on-success="show-modal"
  data-modal="#confirmModal"
>...</form>

<div id="confirmModal" class="modal hidden">
  <!-- Shows after success -->
</div>
```

### `close-modal`
Close modal. Requires `data-modal`.

```html
<form
  data-on-success="close-modal"
  data-modal="#myModal"
>...</form>
```

---

## Custom Actions

### `custom`
Execute registered custom action. Requires `custom-action`.

```html
<form
  data-on-success="custom"
  custom-action="updateCart"
>...</form>

<button
  data-ajax
  data-on-success="custom"
  custom-action="updateCart"
>Add to Cart</button>
```

**Register action:**
```javascript
import { registerAction } from './api/ajax-handler.js';

registerAction('updateCart', (data) => {
  const badge = document.querySelector('[data-count]');
  badge.textContent = data.count;
});
```

---

## Multiple Actions

Execute multiple actions in sequence:

```html
<!-- Space-separated -->
<form data-on-success="prepend toggle reset">...</form>

<!-- Comma-separated -->
<form data-on-success="append, custom, reload" data-action="myAction">...</form>
```

Actions execute in order: prepend → toggle → reset

---

## Error Actions

Same actions available for `data-on-error`:

```html
<form data-on-error="show-modal" data-modal="#errorModal">...</form>
<form data-on-error="remove" data-target=".temp-item">...</form>
```
