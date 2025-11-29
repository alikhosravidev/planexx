# BaseWebController - View Rendering Foundation

## 🎯 Golden Rule & Architecture

**Primary Approach: Client-Side API Calls**

The admin panel follows a **modern SPA-like architecture** where:

1. **Client (Axios) → API directly** for all CRUD operations (create, update, delete, actions)
2. **BaseWebController → API → View** only for rendering pages (index, show, edit, create forms)

### Architecture Flow

```
┌─────────────────────────────────────────────────────────────┐
│                     Admin Panel (Browser)                    │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  ┌──────────────────┐         ┌──────────────────┐          │
│  │  Page Rendering  │         │  User Actions    │          │
│  │  (Initial Load)  │         │  (CRUD/Submit)   │          │
│  └────────┬─────────┘         └────────┬─────────┘          │
│           │                            │                     │
│           │ GET /admin/users           │ Axios POST/PATCH   │
│           │                            │ DELETE directly    │
│           ▼                            ▼                     │
│  ┌──────────────────┐         ┌──────────────────┐          │
│  │ BaseWebController│         │   Direct API     │          │
│  │  (View Only)     │         │   Calls          │          │
│  └────────┬─────────┘         └────────┬─────────┘          │
│           │                            │                     │
│           │ forwardToApi()             │ HTTP Request        │
│           │ (Internal)                 │ (with CSRF token)   │
└───────────┼────────────────────────────┼─────────────────────┘
            │                            │
            ▼                            ▼
    ┌───────────────────────────────────────────┐
    │           Internal API Layer              │
    │  (Validation, Auth, Business Logic)       │
    └───────────────────────────────────────────┘
```

### ⛔ Absolute Prohibition

Any **direct** calls to services, Actions, or Eloquent models (such as `User::find()`) in admin panel controllers **are prohibited**.

**Your web controller only talks to the API for data. No less, no more.**

> 📖 **See examples**: [BaseWebController Usage Examples](../examples/base-web-controller-usage.md#governing-principles)
> 📖 **See best practices**: [Web Panel Best Practices](web-panel-best-practices.md#1-absolute-prohibitions)

---

## Overview

`BaseWebController` is the foundation for all Admin Panel (web) controllers, ensuring true **API-FIRST architecture** where the web panel acts as a first-class API client.

**Location**: `app/Contracts/Controller/BaseWebController.php`

## Purpose

`BaseWebController` serves **two primary purposes**:

### 1. 📝 View Rendering (Primary Use)
Render initial page views by fetching data from the API:
- ✅ **Index pages** with lists and filters
- ✅ **Show pages** with detailed data
- ✅ **Create/Edit forms** with initial data

### 2. 🔄 Fallback for Form Submissions (Legacy Support)
Handle traditional form submissions when Axios is not suitable:
- ⚠️ **File uploads** with progress tracking
- ⚠️ **Multi-step forms** requiring server-side state
- ⚠️ **Non-JavaScript fallback** for accessibility

### 🎯 Recommended Approach

```javascript
// ✅ PREFERRED: Direct Axios calls for actions
axios.post('/api/users', formData)
  .then(response => {
    // Handle success
    showNotification('User created successfully');
    refreshTable();
  })
  .catch(error => {
    // Handle validation errors
    displayErrors(error.response.data.errors);
  });
```

```php
// ✅ ACCEPTABLE: BaseWebController for view rendering
public function index(Request $request): View
{
    $response = $this->apiGet('users', $request->all());
    return view('admin.users.index', ['users' => $response['data']]);
}

// ⚠️ FALLBACK ONLY: For traditional form submissions
public function store(Request $request): RedirectResponse
{
    $this->apiPost('users', $request->all());
    return redirect()->route('admin.users.index');
}
```

## Core Concept

```
┌──────────────┐
│  Admin Panel │ (Web Controllers extend BaseWebController)
└──────┬───────┘
       │ Internal API Call (no network overhead)
       ▼
┌──────────────────────────────────────────────┐
│          Full API Stack                      │
│  ┌────────────────────────────────────────┐  │
│  │ 1. Middleware (auth, throttle, etc.)   │  │
│  │ 2. FormRequest Validation              │  │
│  │ 3. Controller + Service Layer          │  │
│  │ 4. Repository                          │  │
│  │ 5. Transformer (Data Transformation)   │  │
│  └────────────────────────────────────────┘  │
└──────────────────────────────────────────────┘
       │
       ▼ Same JSON Response
┌──────────────┐
│ External API │ (Mobile, Web, Third-party)
│   Clients    │
└──────────────┘
```

**Key Insight**: Admin panel receives the **exact same** JSON response that external clients receive.

## Main Method: `forwardToApi`

```php
$response = $this->forwardToApi(string $routeName, array $data = [], string $method = 'POST', array $headers = []);
```

**Parameters:**
- `$routeName`: API route name (e.g.: `'api.v1.admin.user.auth'`, `'api.users.index'`, `'api.departments.show'`)
- `$data`: (optional) Array of data to send (for POST/PUT/PATCH or query params for GET)
- `$method`: (optional) HTTP method - 'GET', 'POST', 'PUT', 'PATCH', 'DELETE' (default: 'POST')
- `$headers`: (optional) Custom headers array

**Automatic behavior:**
- Automatically authenticates the logged-in user
- Automatically handles validation errors (422) and redirects user back to form

> 📖 **Error handling details**: [Web Panel Best Practices](web-panel-best-practices.md#3-error-handling)

---

## Basic Usage

### Step 1: Extend BaseWebController

```php
<?php

namespace App\Core\User\Http\Controllers\Web;

use App\Contracts\Controller\BaseWebController;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserManagementController extends BaseWebController
{
    public function index(Request $request): View
    {
        // Request to internal API using route name
        $response = $this->forwardToApi('api.addresses.index', $request->all(), 'GET');

        return view('admin.users.index', [
            'users' => $response['data'] ?? [],
            'pagination' => $response['meta']['pagination'] ?? [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Optional: simple panel validation for better UX (before API)
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'mobile' => 'required|regex:/^09\d{9}$/',
        ]);

        // All API validation (FormRequest) is automatically handled
        $response = $this->forwardToApi('api.addresses.store', $validatedData, 'POST');

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully');

        // Note: try-catch for 422 errors is optional!
        // BaseWebController automatically handles it.
    }
}
```

## Helper Methods

For cleaner syntax, you can use helper methods:

```php
// GET request
$response = $this->apiGet('api.addresses.index', $queryParams);

// POST request
$response = $this->apiPost('api.addresses.store', $data);

// PUT request (full update)
$response = $this->apiPut('api.addresses.update', $data);

// PATCH request (partial update)
$response = $this->apiPatch('api.addresses.update', $data);

// DELETE request
$response = $this->apiDelete('api.addresses.destroy');
```

**With custom headers:**
```php
$response = $this->apiGet('api.users.index', $data, [
    'X-Custom-Header' => 'value',
    'X-Trace-Id' => request()->header('X-Trace-Id'),
]);
```

**Both `forwardToApi()` and helper methods are equally recommended.**

## Features

### 1. Automatic Validation

```php
public function store(Request $request)
{
    try {
        // No need to duplicate validation rules!
        // StoreAddressRequest (FormRequest) handles validation
        $this->apiPost('api.addresses.store', $request->all());
    } catch (ValidationException $e) {
        // Auto-redirects back with errors
        throw $e;
    }
}
```

### 2. Consistent Data Transformation

```php
public function show(int $id): View
{
    // Receives exact same transformed data as mobile app
    $response = $this->apiGet('api.addresses.show', ['id' => $id]);

    // Data is already transformed via AddressTransformer
    return view('admin.addresses.show', [
        'address' => $response['data'],
    ]);
}
```

### 3. Authorization Enforcement

```php
public function destroy(int $id)
{
    // API Policy checks are enforced automatically
    // If user lacks permission, API returns 403
    $this->apiDelete('api.addresses.destroy', ['id' => $id]);
}
```

### 4. Filter and Sort Support

```php
$response = $this->apiGet('api.departments.index', [
    'filter' => [
        'name' => ['like' => '%engineering%'],
        'is_active' => 1,
    ],
    'sort' => '-created_at',
    'per_page' => 20,
    'includes' => ['manager', 'employees'],
]);
```

## Authentication Bridge

The controller automatically bridges web session authentication to API token authentication:

```php
protected function forwardToApi(...)
{
    // Get user from web session
    $webUser = auth('web')->user();

    if ($webUser) {
        // Tell Sanctum to act as this user for the API request
        Sanctum::actingAs($webUser, abilities: ['*'], guard: 'sanctum');
    }

    // Request is dispatched with proper authentication
    $response = app()->handle($request);

    // ...
}
```

**Result**: API sees the web user as authenticated via Sanctum, so all Policies and Gates work correctly.

## Configuration

### API Prefix

The `$apiPrefix` property is available for future extensibility:

```php
protected string $apiPrefix = ''; // Default value
```

**Note:** Currently, this property is reserved for future use. The system uses route names directly (e.g., `'api.users.index'`) which are resolved by Laravel's routing system.

> 📖 **Configuration & troubleshooting**: See [BaseWebController Usage Examples](base-web-controller-usage.md#configuration)

## Common Patterns

### Pattern 1: List with Pagination

```php
public function index(Request $request): View
{
    $response = $this->apiGet('api.addresses.index', [
        'per_page' => 15,
        'page' => $request->get('page', 1),
        'sort' => '-created_at',
    ]);

    return view('admin.addresses.index', [
        'addresses' => $response['data'],
        'pagination' => $response['meta']['pagination'],
    ]);
}
```

### Pattern 2: Show with Includes

```php
public function show(int $id): View
{
    $response = $this->apiGet('api.addresses.show', [
        'id' => $id,
        'includes' => ['city', 'user'],
    ]);

    return view('admin.addresses.show', [
        'address' => $response['data'],
    ]);
}
```

### Pattern 3: Create with Validation

```php
public function store(Request $request): RedirectResponse
{
    try {
        $this->apiPost('api.addresses.store', $request->all());
        return redirect()->route('admin.addresses.index')
            ->with('success', 'Address created successfully');
    } catch (ValidationException $e) {
        throw $e; // Redirects back with errors
    }
}
```

### Pattern 4: Update with Validation

```php
public function update(Request $request, int $id): RedirectResponse
{
    try {
        $this->apiPatch('api.addresses.update', array_merge(['id' => $id], $request->all()));
        return redirect()->route('admin.addresses.show', $id)
            ->with('success', 'Address updated successfully');
    } catch (ValidationException $e) {
        throw $e;
    }
}
```

### Pattern 5: Delete

```php
public function destroy(int $id): RedirectResponse
{
    $this->apiDelete('api.addresses.destroy', ['id' => $id]);

    return redirect()->route('admin.addresses.index')
        ->with('success', 'Address deleted successfully');
}
```

## Related Documentation

- **Full Examples**: `.claude/examples/base-web-controller-usage.md`
- **Base Controller**: `.claude/presentation/controllers.md`
- **Transformers**: `.claude/presentation/transformers.md`
- **API Design**: `.claude/api/design.md`

## When to Use BaseWebController

### ✅ Use for View Rendering

```php
// Index page with data
public function index(Request $request): View
{
    $response = $this->apiGet('api.users.index', [
        'filter' => $request->get('filter', []),
        'sort' => $request->get('sort', '-created_at'),
        'per_page' => 15,
    ]);
    
    return view('admin.users.index', [
        'users' => $response['data'],
        'meta' => $response['meta'],
    ]);
}

// Show page
public function show(int $id): View
{
    $response = $this->apiGet('api.users.show', [
        'id' => $id,
        'includes' => ['department', 'jobPosition'],
    ]);
    
    return view('admin.users.show', ['user' => $response['data']]);
}

// Create/Edit forms with initial data
public function edit(int $id): View
{
    $user = $this->apiGet('api.users.show', ['id' => $id]);
    $roles = $this->apiGet('api.roles.index');
    
    return view('admin.users.edit', [
        'user' => $user['data'],
        'roles' => $roles['data'],
    ]);
}
```

### ❌ Do NOT Use for Actions

```php
// ❌ WRONG: Don't use BaseWebController for CRUD actions
public function store(Request $request): RedirectResponse
{
    $this->apiPost('api.users.store', $request->all());
    return redirect()->route('admin.users.index');
}

public function update(Request $request, int $id): RedirectResponse
{
    $this->apiPatch('api.users.update', array_merge(['id' => $id], $request->all()));
    return redirect()->route('admin.users.show', $id);
}

public function destroy(int $id): RedirectResponse
{
    $this->apiDelete('api.users.destroy', ['id' => $id]);
    return redirect()->route('admin.users.index');
}
```

### ✅ Use Axios Instead

```javascript
// ✅ CORRECT: Use Axios for all CRUD actions

// Create
const createUser = async (formData) => {
    try {
        const response = await axios.post('/users', formData);
        showSuccess('User created successfully');
        await refreshUserTable();
    } catch (error) {
        if (error.response?.status === 422) {
            displayValidationErrors(error.response.data.errors);
        }
    }
};

// Update
const updateUser = async (userId, formData) => {
    const response = await axios.patch(`/users/${userId}`, formData);
    showSuccess('User updated successfully');
};

// Delete
const deleteUser = async (userId) => {
    if (confirm('Are you sure?')) {
        await axios.delete(`/users/${userId}`);
        showSuccess('User deleted successfully');
        await refreshUserTable();
    }
};
```


## Summary

`BaseWebController` is designed for **view rendering** in a modern admin panel architecture:

### 🎯 Primary Use Cases

1. **✅ View Rendering** (Recommended)
   - Index pages with data lists
   - Show pages with details
   - Create/Edit forms with initial data

2. **⚠️ Form Submissions** (Fallback Only)
   - File uploads with progress tracking
   - Multi-step forms requiring server state
   - Non-JavaScript accessibility fallback

### 🚀 Recommended Architecture

**Use Axios for all CRUD operations:**
```javascript
// Create, Update, Delete via Axios
axios.post('/users', formData);
axios.patch(`/users/${id}`, formData);
axios.delete(`/users/${id}`);
```

**Use BaseWebController for views only:**
```php
// Only GET routes for rendering pages
public function index(Request $request): View {
    $response = $this->apiGet('api.users.index', $request->all());
    return view('admin.users.index', ['users' => $response['data']]);
}
```

> 📖 **Complete benefits table**: [BaseWebController Usage Examples](../examples/base-web-controller-usage.md#summary-of-important-notes)
> 📖 **Best practices**: [Web Panel Best Practices](web-panel-best-practices.md)
