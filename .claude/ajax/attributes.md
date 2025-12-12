# AJAX Attributes Reference

## Core Attributes

### `data-ajax`
**Required.** Enables AJAX handling.

```html
<form data-ajax>...</form>
```

### `action` / `data-action`
**Required.** Endpoint URL. Use `action` on forms and `data-action` (or `href`) on buttons/links.

```html
<form action="{{ route('users.store') }}">...</form>
<button data-action="{{ route('users.destroy') }}" data-ajax>Delete</button>
```

### `method`
**Optional.** HTTP method. Default: POST.

```html
<form method="POST">...</form>
<form method="PUT">...</form>
<form method="DELETE">...</form>
```

### `@csrf`
**Required.** CSRF token.

```html
<form>
  @csrf
  ...
</form>
```

---

## Response Handling

### `data-on-success`
What to do after successful submission. Can specify multiple actions.

```html
<!-- Single action -->
<form data-on-success="reload">...</form>

<!-- Multiple actions (space or comma separated) -->
<form data-on-success="prepend reload">...</form>
<form data-on-success="prepend, toggle, custom" data-action="myAction">...</form>
```

**Valid values:** reload, redirect, replace, append, prepend, remove, toggle, reset, add-class, remove-class, show-modal, close-modal, back, custom

### `data-on-error`
What to do if submission fails.

```html
<form data-on-error="show-modal" data-modal="#errorModal">...</form>
```

### `custom-action`
Custom action name (used with `data-on-success="custom"`). Specifies which registered custom action to execute.

```html
<form data-on-success="custom" custom-action="updateCart">...</form>
<button data-ajax data-on-success="custom" custom-action="loginSuccess">Login</button>
```

---

## DOM Targeting

### `data-target`
CSS selector of element to modify (for replace, append, prepend, remove, toggle, etc.).

```html
<form data-on-success="replace" data-target="#products">...</form>
<form data-on-success="remove" data-target=".item[data-id='123']">...</form>
```

### `data-modal`
Modal selector (for show-modal, close-modal).

```html
<form data-on-success="close-modal" data-modal="#myModal">...</form>
```

### `data-class`
Space-separated CSS classes (for add-class, remove-class).

```html
<form data-on-success="add-class" data-target="#msg" data-class="success text-green">...</form>
<form data-on-success="remove-class" data-target=".item" data-class="disabled">...</form>
```

---

## Validation & Messages

### `data-validate`
Enable/disable client-side validation. Default: true.

```html
<form data-validate="true">...</form>
<form data-validate="false">...</form>
```

### `data-show-message`
Show success/error toast messages. Default: true.

```html
<form data-show-message="false">...</form>
```

### `data-confirm` (buttons/links)
Confirmation prompt before sending the request.

```html
<button
  data-ajax
  data-action="{{ route('posts.destroy', $post->id) }}"
  data-method="DELETE"
  data-confirm="Are you sure?"
>
  Delete
</button>
```

### `data-validation-error`
Custom validation error message.

```html
<form data-validation-error="Please fill all required fields">...</form>
```

---

## UI States

### `data-loading-class`
CSS class to add during request. Useful for disabling buttons/forms.

```html
<form data-loading-class="opacity-50 pointer-events-none">
  <button type="submit">Save</button>
</form>

<!-- Also supported on buttons/links -->
<button
  data-ajax
  data-action="/api/items"
  data-loading-class="opacity-50"
>
  Create
</button>
```

### `data-param-*` (buttons/links)
Send extra payload without a form. Each `data-param-foo="bar"` becomes `foo: "bar"` in the request body (or query for GET).

```html
<button
  data-ajax
  data-action="{{ route('posts.store') }}"
  data-param-title="Hello"
  data-param-status="draft"
>
  Quick Add
</button>
```

---

## Complete Form Example

```html
<form
  data-ajax                                      <!-- Enable AJAX -->
  action="{{ route('posts.store') }}"           <!-- Endpoint -->
  method="POST"                                  <!-- HTTP method -->
  data-on-success="prepend reload"              <!-- Success actions -->
  data-on-error="show-modal"                    <!-- Error action -->
  data-target="#posts-list"                     <!-- Target element -->
  data-modal="#errorModal"                      <!-- Error modal -->
  data-validate="true"                          <!-- Validation enabled -->
  data-show-message="true"                      <!-- Show messages -->
  data-loading-class="opacity-50"               <!-- Loading state -->
>
  @csrf
  <input type="text" name="title" required />
  <textarea name="content" required></textarea>
  <button type="submit">Publish</button>
</form>
```

## Complete Button Example

```html
<!-- Simple delete button -->
<button
  data-ajax                                      <!-- Enable AJAX -->
  action="{{ route('posts.destroy', $post->id) }}"  <!-- Endpoint -->
  data-method="DELETE"                          <!-- HTTP method -->
  data-on-success="remove"                      <!-- Success action -->
  data-target="[data-post-id='{{ $post->id }}']" <!-- Target element -->
  data-show-message="true"                      <!-- Show message -->
  class="btn btn-danger"
>
  Delete
</button>

<!-- Custom action button -->
<button
  data-ajax
  action="{{ route('cart.add') }}"
  data-on-success="custom"
  custom-action="updateCart"
  data-show-message="false"
  class="btn btn-primary"
>
  Add to Cart
</button>
```
