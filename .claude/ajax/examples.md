# AJAX System - Examples

## Simple Form with Reload

```html
<form
  data-ajax
  action="{{ route('users.store') }}"
  method="POST"
  data-on-success="reload"
>
  @csrf
  <input type="text" name="name" required />
  <input type="email" name="email" required />
  <button>Save</button>
</form>
```

---

## Create with Prepend to List

```html
<form
  data-ajax
  action="{{ route('posts.store') }}"
  method="POST"
  data-on-success="prepend"
  data-target="#posts-list"
>
  @csrf
  <textarea name="content" required></textarea>
  <button>Post</button>
</form>

<div id="posts-list">
  @foreach($posts as $post)
    <div data-post-id="{{ $post->id }}">
      {{ $post->content }}
    </div>
  @endforeach
</div>
```

---

## Update Single Item

```html
<form
  data-ajax
  action="{{ route('posts.update', $post->id) }}"
  method="PUT"
  data-on-success="replace"
  data-target="[data-post-id='{{ $post->id }}']"
>
  @csrf
  <textarea name="content" required>{{ $post->content }}</textarea>
  <button>Update</button>
</form>
```

---

## Delete Button with Remove Action

```html
<button
  data-ajax
  action="{{ route('posts.destroy', $post->id) }}"
  data-method="DELETE"
  data-on-success="remove"
  data-target="[data-post-id='{{ $post->id }}']"
  class="btn btn-danger btn-sm"
  onclick="return confirm('Sure?')"
>
  Delete
</button>
```

**Notes:**
- `data-ajax` enables AJAX handling
- `action` is the endpoint URL
- `data-method` specifies HTTP method (DELETE, POST, etc.)
- `data-on-success="remove"` removes the element after success
- `data-target` selects which element to remove

---

## Filter with Replace

```html
<form
  data-ajax
  action="{{ route('products.filter') }}"
  method="POST"
  data-on-success="replace"
  data-target="#products"
>
  <select name="category">
    <option value="">All Categories</option>
    @foreach($categories as $cat)
      <option value="{{ $cat->id }}">{{ $cat->name }}</option>
    @endforeach
  </select>
  <button>Filter</button>
</form>

<div id="products">
  @include('products.list')
</div>
```

---

## Modal Form

```html
<!-- Open button -->
<button onclick="document.getElementById('modal').classList.remove('hidden')">
  Add New
</button>

<!-- Modal -->
<div id="modal" class="modal hidden">
  <form
    data-ajax
    action="{{ route('items.store') }}"
    method="POST"
    data-on-success="append close-modal"
    data-target="#items"
    data-modal="#modal"
  >
    @csrf
    <input type="text" name="name" required />
    <button>Save</button>
  </form>
</div>

<!-- Items list -->
<div id="items">
  @foreach($items as $item)
    <div>{{ $item->name }}</div>
  @endforeach
</div>
```

---

## Custom Action - Update Cart Badge (Form)

```html
<form
  data-ajax
  action="{{ route('cart.add') }}"
  method="POST"
  data-on-success="custom"
  custom-action="updateCart"
  data-show-message="false"
>
  @csrf
  <input type="hidden" name="product_id" value="{{ $product->id }}" />
  <input type="number" name="quantity" value="1" />
  <button>Add to Cart</button>
</form>

<span data-cart-count>0</span>
```

```javascript
// app.js or forms.js
import { registerAction } from './api/ajax-handler.js';

registerAction('updateCart', (data) => {
  document.querySelector('[data-cart-count]').textContent = data.count;
});
```

---

## Custom Action - Button

```html
<button
  data-ajax
  action="{{ route('cart.add') }}"
  data-method="POST"
  data-on-success="custom"
  custom-action="updateCart"
  data-show-message="false"
  class="btn btn-primary"
>
  Add to Cart
</button>

<span data-cart-count>0</span>
```

Works the same way as form example above.

---

## Multi-step Form (Authentication Example)

```html
<!-- Step 1: Mobile Input -->
<div id="step-mobile">
  <form
    data-ajax
    action="{{ route('user.initiate.auth') }}"
    method="GET"
    data-on-success="custom"
    custom-action="show-otp-step"
  >
    <input type="tel" name="identifier" required />
    <input type="hidden" name="authType" value="otp">
    <button>Get OTP</button>
  </form>
</div>

<!-- Step 2: OTP Input -->
<div id="step-otp" class="hidden">
  <form
    data-ajax
    action="{{ route('user.auth') }}"
    method="POST"
    data-on-success="redirect"
  >
    @csrf
    <input type="hidden" name="identifier" id="otp-identifier">
    <input type="hidden" name="password" id="otp-password">
    <input type="hidden" name="authType" value="otp">
    <!-- OTP input fields -->
    <button type="submit">Login</button>
  </form>

  <!-- Resend button using AJAX -->
  <button
    id="resend-button"
    data-ajax
    action="{{ route('user.initiate.auth') }}"
    data-method="GET"
    data-on-success="custom"
    custom-action="resend-success"
    data-show-message="false"
  >
    Resend Code
  </button>
</div>
```

```javascript
import { registerAction } from './api/ajax-handler.js';

// Register custom action for multi-step flow
registerAction('show-otp-step', (data) => {
  // Show OTP step, hide mobile step
  document.getElementById('step-mobile').classList.add('hidden');
  document.getElementById('step-otp').classList.remove('hidden');

  // Store identifier for OTP submission
  const identifier = data?.identifier;
  if (identifier) {
    document.getElementById('otp-identifier').value = identifier;
    document.getElementById('mobile-display').textContent = identifier;
  }
});

registerAction('resend-success', (data) => {
  // Show success message and clear OTP inputs
  const otpInputs = document.querySelectorAll('.otp-input');
  otpInputs.forEach(input => input.value = '');
  otpInputs[0]?.focus();
});
```

**Note:** After successful login, the server sets an HttpOnly cookie and returns `redirect_url` in the response. The `redirect` action automatically handles the redirect. No client-side token storage is needed.

---

## Loading State

```html
<form
  data-ajax
  action="{{ route('users.store') }}"
  data-loading-class="opacity-50 pointer-events-none"
>
  @csrf
  <input type="text" name="name" required />
  <button type="submit">Save</button>
  <!-- Button disabled during request -->
</form>
```

---

## Redirect after Save

```html
<form
  data-ajax
  action="{{ route('users.store') }}"
  method="POST"
  data-on-success="redirect"
>
  @csrf
  <input type="text" name="name" required />
  <button>Save</button>
</form>
```

**API Response:**
```json
{
  "status": true,
  "message": "User created",
  "redirect_url": "/users"
}
```

---

## Add CSS Class on Success

```html
<form
  data-ajax
  action="{{ route('settings.update') }}"
  method="PUT"
  data-on-success="add-class"
  data-target="#form-container"
  data-class="success"
>
  @csrf
  <input type="text" name="setting" />
  <button>Save</button>
</form>
```

CSS:
```css
#form-container.success {
  border: 2px solid green;
}
```
