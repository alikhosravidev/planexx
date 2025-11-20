# BaseWebController - Practical Examples

## 🎯 Architecture Overview

**Modern Admin Panel Architecture:**

1. **Axios → API** for all CRUD operations (create, update, delete)
2. **BaseWebController → API → View** for page rendering only

## Governing Principles

1. **Primary Approach**: Use Axios for all user actions and CRUD operations
2. **BaseWebController**: Only for rendering views (index, show, create, edit pages)
3. **Absolute Prohibition**: No direct calls to services/Eloquent/Action
4. **All data from API**: Whether via Axios or BaseWebController

> 📖 **Core concepts**: [BaseWebController Foundation](../presentation/base-web-controller.md#golden-rule--architecture)

## Two-Layer Validation

**Panel + API validation for optimal UX and security.**

### Layer 1: Panel Validation (UX)
- Quick validation for immediate user feedback
- Basic field validation (required, format, etc.)

### Layer 2: API Validation (Business Rules)
- Complete business logic validation
- Database constraints and relationships
- Complex business rules

> 📖 **Complete examples**: [Web Panel Best Practices](web-panel-best-practices.md#2-validation-two-layers)

## Example 1: User Management (Recommended Approach)

### Routes Definition

```php
// Only define routes for VIEW rendering
Route::middleware(['web', 'auth'])->prefix('admin')->group(function () {
    Route::get('users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::get('users/create', [UserManagementController::class, 'create'])->name('admin.users.create');
    Route::get('users/{id}', [UserManagementController::class, 'show'])->name('admin.users.show');
    Route::get('users/{id}/edit', [UserManagementController::class, 'edit'])->name('admin.users.edit');
    
    // NO POST/PATCH/DELETE routes needed - Axios calls API directly
});
```

### Controller (View Rendering Only)

```php
<?php

namespace App\Core\User\Http\Controllers\Web;

use App\Contracts\Controller\BaseWebController;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserManagementController extends BaseWebController
{
    /**
     * Display users list page
     */
    public function index(Request $request): View
    {
        // Fetch data for initial page render
        $response = $this->forwardToApi('GET', 'users', $request->all());

        return view('admin.users.index', [
            'users' => $response['data'] ?? [],
            'pagination' => $response['meta']['pagination'] ?? [],
        ]);
    }

    /**
     * Show create form
     */
    public function create(): View
    {
        // Fetch related data (e.g., roles)
        $rolesResponse = $this->forwardToApi('GET', 'roles');

        return view('admin.users.create', [
            'roles' => $rolesResponse['data'] ?? []
        ]);
    }

    /**
     * Show user details
     */
    public function show(int $id): View
    {
        $response = $this->forwardToApi('GET', "users/{$id}", [
            'includes' => ['department', 'jobPosition'],
        ]);

        return view('admin.users.show', [
            'user' => $response['data'] ?? [],
        ]);
    }

    /**
     * Show edit form
     */
    public function edit(int $id): View
    {
        $user = $this->forwardToApi('GET', "users/{$id}");
        $roles = $this->forwardToApi('GET', 'roles');

        return view('admin.users.edit', [
            'user' => $user['data'] ?? [],
            'roles' => $roles['data'] ?? [],
        ]);
    }
}
```

### Frontend (Axios for Actions)

```javascript
// resources/js/admin/users.js

// Create User
const createUserForm = document.getElementById('create-user-form');
createUserForm?.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    
    try {
        const response = await axios.post('/users', data);
        
        // Show success message
        showNotification('User created successfully', 'success');
        
        // Redirect to users list
        window.location.href = '/admin/users';
    } catch (error) {
        if (error.response?.status === 422) {
            // Display validation errors
            displayValidationErrors(error.response.data.errors);
        } else {
            showNotification('An error occurred', 'error');
        }
    }
});

// Update User
const updateUser = async (userId) => {
    const formData = new FormData(document.getElementById('edit-user-form'));
    const data = Object.fromEntries(formData);
    
    try {
        await axios.patch(`/users/${userId}`, data);
        showNotification('User updated successfully', 'success');
        window.location.href = `/admin/users/${userId}`;
    } catch (error) {
        if (error.response?.status === 422) {
            displayValidationErrors(error.response.data.errors);
        }
    }
};

// Delete User
const deleteUser = async (userId) => {
    if (!confirm('Are you sure you want to delete this user?')) {
        return;
    }
    
    try {
        await axios.delete(`/users/${userId}`);
        showNotification('User deleted successfully', 'success');
        
        // Refresh the table or redirect
        window.location.href = '/admin/users';
    } catch (error) {
        if (error.response?.status === 403) {
            showNotification('You do not have permission', 'error');
        } else {
            showNotification('An error occurred', 'error');
        }
    }
};

// Helper: Display validation errors
function displayValidationErrors(errors) {
    // Clear previous errors
    document.querySelectorAll('.error-message').forEach(el => el.remove());
    
    // Display new errors
    Object.keys(errors).forEach(field => {
        const input = document.querySelector(`[name="${field}"]`);
        if (input) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message text-red-500 text-sm mt-1';
            errorDiv.textContent = errors[field][0];
            input.parentElement.appendChild(errorDiv);
        }
    });
}

// Helper: Show notification
function showNotification(message, type = 'info') {
    // Implement your notification system
    console.log(`[${type}] ${message}`);
}
```

---

## Example 2: Dashboard with Multiple API Calls

```php
public function dashboard(): View
{
    // Fetch multiple data sources for dashboard
    $recentUsers = $this->forwardToApi('GET', 'users', [
        'sort' => '-created_at',
        'per_page' => 5,
    ]);

    $departments = $this->forwardToApi('GET', 'departments', [
        'per_page' => 5,
    ]);

    $statistics = $this->forwardToApi('GET', 'statistics/summary');

    return view('admin.dashboard', [
        'recent_users' => $recentUsers['data'] ?? [],
        'departments' => $departments['data'] ?? [],
        'stats' => $statistics['data'] ?? [],
    ]);
}
```

---

## Example: Export (with custom headers)

```php
public function export(Request $request): Response
{
    $response = $this->forwardToApi(
        'GET',
        'users/export',
        ['format' => 'csv'],
        ['X-Export-Format' => 'csv']
    );

    return response($response['data'])
        ->header('Content-Type', 'text/csv')
        ->header('Content-Disposition', 'attachment; filename="users.csv"');
}
```

## Important Notes

### 1. Validation errors (422) are automatically handled

```php
public function store(Request $request): RedirectResponse
{
    // No try-catch needed for 422!
    $response = $this->forwardToApi('POST', 'users', $request->all());

    // If API returns validation error:
    // BaseWebController automatically redirects user
    // and flashes errors + old input

    return redirect()->route('admin.users.index');
}
```

### 2. Automatic Transformer

```php
public function show(int $id): View
{
    // Data you receive is **exactly** the same
    // that mobile client receives
    $response = $this->forwardToApi('GET', "users/{$id}");

    // $response['data'] has already been transformed by API Transformer
    // No manual transformation needed

    return view('admin.users.show', [
        'user' => $response['data'],
    ]);
}
```

### 3. Automatic Authorization (Policy/Gate)

```php
public function sensitiveAction(int $id): RedirectResponse
{
    try {
        // API Policy automatically checks
        $response = $this->forwardToApi('POST', "users/{$id}/deactivate");

        return redirect()->back()->with('success', 'Done!');

    } catch (\Exception $e) {
        // 403 if user has no permission
        abort(403, 'Unauthorized operation');
    }
}
```

## Route Definition

```php
<?php

use App\Core\User\Http\Controllers\Web\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('admin')->group(function () {
    // Standard CRUD routes
    Route::resource('users', UserManagementController::class);

    // Custom actions
    Route::post('users/bulk-deactivate', [UserManagementController::class, 'bulkDeactivate'])
        ->name('users.bulk-deactivate');
});
```

## Summary of Important Notes

| Note | Description |
|------|-------------|
| **No Duplicate Validation** | API's FormRequest handles all validation |
| **Identical Data** | Panel and mobile client receive **same** JSON |
| **Complete Middleware** | All API middleware is applied (auth, rate limit) |
| **No Network Overhead** | Everything is dispatched internally |
| **Error Handling** | 422 errors automatically converted to redirects |
| **Auth Bridge** | Web session automatically authenticates API |

---

## Absolute Prohibitions

```php
// ❌ Forbidden
User::find($id);
User::all();
app(UserService::class)->getUsers();
app(UserRepository::class)->find($id);
$user->update(['is_active' => true]);

// ✅ Only correct way
$this->forwardToApi('GET', "users/{$id}");
$this->forwardToApi('GET', 'users');
$this->forwardToApi('PATCH', "users/{$id}", ['is_active' => true]);
```

> 📖 **More details**: [Web Panel Best Practices](web-panel-best-practices.md#1-absolute-prohibitions)

## Configuration

If your API uses `/api` prefix, change it:

```php
// In BaseWebController
abstract class BaseWebController extends BaseController
{
    protected string $apiPrefix = '/api'; // Change from ''
}
```

For this project: `$apiPrefix = ''` (without prefix)

## Troubleshooting

### Issue: "Unauthenticated" error

**Solution**: Make sure user is logged in via `auth('web')` middleware

### Issue: 422 errors not displayed in form

**Solution**: Use `@error()` and `old()` in Blade template:

```blade
<input name="mobile" value="{{ old('mobile') }}">
@error('mobile')
    <span class="error">{{ $message }}</span>
@enderror
```

### Issue: Wrong API address being called

**Solution**: Check `$apiPrefix` value (for this project: `''`)

## Testing

```php
<?php

namespace Tests\Feature\Web;

use Tests\TestCase;
use App\Core\User\Entities\User;

class UserManagementControllerTest extends TestCase
{
    public function test_admin_can_view_users_list(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin, 'web')
            ->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertViewIs('admin.users.index');
        $response->assertViewHas('users');
    }

    public function test_admin_can_create_user(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin, 'web')
            ->post(route('admin.users.store'), [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'mobile' => '09123456789',
            ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');
    }
}
```

---

## Summary

`BaseWebController` transforms your admin panel into a true API-FIRST client, ensuring:
- ✅ No code duplication
- ✅ Consistent validation and transformation
- ✅ Full middleware protection
- ✅ Easy maintenance
- ✅ Better testing

Use it as the base class for all your admin panel controllers!

> 📖 **Core summary**: [BaseWebController Foundation](base-web-controller.md#summary)
