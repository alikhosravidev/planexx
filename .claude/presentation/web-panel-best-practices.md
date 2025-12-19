# Best Practices - Developing Admin Panel with BaseWebController

## The Golden Rule

**Modern Admin Panel Architecture:**

```
Admin Panel (Browser)
  │
  ├── Page Rendering → BaseWebController → forwardToApi() → API → View
  │                     (GET routes only)
  │
  └── User Actions → Axios → API directly → JSON Response
                    (POST/PATCH/DELETE)
```

**Priority:**
1. **✅ PRIMARY**: Axios calls API directly for all CRUD operations
2. **✅ SECONDARY**: BaseWebController only for rendering views
3. **⚠️ FALLBACK**: BaseWebController for form submissions (legacy/special cases)

> 📖 **Core foundation**: [BaseWebController Foundation](base-web-controller.md#overview)

---

## 1. Absolute Prohibitions

### Forbidden in Web Controllers

```php
// User class
User::all();                          // Forbidden
User::find($id);                      // Forbidden
User::where('is_active', true)->get(); // Forbidden
User::factory()->create();            // Forbidden

// Services
app(UserService::class)->getUsers();  // Forbidden
app(CreateUserService::class)->execute($data); // Forbidden

// Repository
app(UserRepository::class)->findAll(); // Forbidden

// Model Updates
$user = User::find($id);
$user->update($data);                 // Forbidden

// Actions
CreateUserAction::dispatch($data);    // Forbidden
```

### Only Correct Way

```php
// All data from API
$users = $this->forwardToApi('GET', 'users');
$user = $this->forwardToApi('GET', "users/{$id}");
$this->forwardToApi('POST', 'users', $data);
$this->forwardToApi('PATCH', "users/{$id}", $data);
$this->forwardToApi('DELETE', "users/{$id}");
```

---

## 2. Validation: Two Layers

### Layer 1: Panel Validation (UX)

```php
public function store(Request $request): RedirectResponse
{
    // Layer 1: Quick definitions for better UX
    // User sees error immediately
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'mobile' => 'required|regex:/^09\d{9}$/',
        'email' => 'nullable|email',
    ]);

    // API goes with validated data
    $response = $this->forwardToApi('POST', 'users', $validated);

    return redirect()->route('admin.users.index');
}
```

### Layer 2: API Validation (Business Rules)

```php
// In API (FormRequest)
class StoreUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'mobile' => 'required|regex:/^09\d{9}$/',
            'email' => 'nullable|email|unique:users',
            'national_code' => 'required|unique:users',
            'job_position_id' => 'required|exists:job_positions,id',
            // ... business rules
        ];
    }
}
```

**Result:**
1. **Panel**: Simple errors (better UX)
2. **API**: Business rules errors (more security)

---

## 3. Error Handling

### 422 Errors (Validation)

```php
// BaseWebController automatically handles it
public function store(Request $request)
{
    // No try-catch needed!
    $this->forwardToApi('POST', 'users', $request->all());

    // If 422 error returns:
    // 1. Session gets flashed
    // 2. User gets redirected
    // 3. Errors get saved in session
}
```

### Other Errors (403, 404, 500)

```php
public function destroy(int $id): RedirectResponse
{
    try {
        $this->forwardToApi('DELETE', "users/{$id}");
    } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
        if ($e->getStatusCode() === 403) {
            abort(403, 'Access denied');
        }
        if ($e->getStatusCode() === 404) {
            abort(404, 'User not found');
        }
    }

    return redirect()->route('admin.users.index');
}
```

---

## 4. Query Parameters and Filters

### Correct Way

```php
public function index(Request $request): View
{
    // Send all query parameters to API
    // API itself manages filtering, sorting and pagination
    $response = $this->forwardToApi('GET', 'users', $request->all());

    return view('admin.users.index', [
        'users' => $response['data'] ?? [],
        'pagination' => $response['meta']['pagination'] ?? [],
    ]);
}
```

### More Detailed

```php
public function search(Request $request): View
{
    $filters = [
        'filter' => [
            'first_name' => ['like' => '%' . $request->get('q') . '%'],
            'is_active' => 1,
        ],
        'sort' => $request->get('sort', '-created_at'),
        'per_page' => min($request->get('per_page', 15), 100),
        'page' => $request->get('page', 1),
    ];

    $response = $this->forwardToApi('GET', 'users', $filters);

    return view('admin.users.search', [
        'users' => $response['data'] ?? [],
    ]);
}
```

---

## 5. Includes and Eager Loading

### Correct: Request from API

```php
public function show(int $id): View
{
    // Request related data inclusion
    $response = $this->forwardToApi('GET', "users/{$id}", [
        'includes' => ['jobPosition', 'department', 'address'],
    ]);

    return view('admin.users.show', [
        'user' => $response['data'],
    ]);
}
```

### Wrong: Loading later

```php
// ❌ Wrong - directly from DB
$user = User::with(['jobPosition', 'department'])->find($id);

// ❌ Wrong - N+1 problem
$users = User::all();
foreach ($users as $user) {
    echo $user->jobPosition->name; // Query for each user!
### Correct

public function index(Request $request): View
{
    $response = $this->forwardToApi('GET', 'users', $request->all());

    // $response structure:
    // {
    //   "data": [...],
    //   "meta": { "pagination": {...} },
    //   "links": {...}
    // }

    return view('admin.users.index', [
        'users' => $response['data'] ?? [],
        'meta' => $response['meta'] ?? [],
    ]);
}
```

### In Blade

```blade
{{-- Total number of users --}}
<p>Total: {{ $meta['pagination']['total'] ?? 0 }}</p>

{{-- List of users --}}
@forelse($users as $user)
    <tr>
        <td>{{ $user['first_name'] }}</td>
        <td>{{ $user['mobile'] }}</td>
    </tr>
@empty
    <tr><td colspan="2">No users found</td></tr>
@endforelse

{{-- Pagination --}}
{{ $meta['pagination'] ?? 'No pagination' }}
```
## 7. Authorization

### Correct: API manages it

```php
public function destroy(int $id): RedirectResponse
{
    try {
        // API Policy automatically checks
        $this->forwardToApi('DELETE', "users/{$id}");
    } catch (\Exception $e) {
        // If policy prevents it
        abort(403, 'You do not have access');
    }

    return redirect()->route('admin.users.index');
}
```

### Wrong: Manual checking

```php
// ❌ Wrong - redundant
public function destroy(int $id)
{
    // This check is redundant
    $this->authorize('delete', User::find($id));

    // Then API again?
    $this->forwardToApi('DELETE', "users/{$id}");
}
```

---

## 8. Rate Limiting and Middleware

### Automatically managed

```php
// API Middleware handles all Throttling
// You don't need to add extra middleware

public function index(Request $request): View
{
    // If API has rate limit:
    // 429 is returned
    // and BaseWebController automatically handles it
    $response = $this->forwardToApi('GET', 'users');

    return view('admin.users.index', [
        'users' => $response['data'] ?? [],
    ]);
}
```

---

## 9. Performance Tips

### 1. Request only what you need

```php
// ✅ Correct - only necessary
$response = $this->forwardToApi('GET', 'users', [
    'includes' => ['jobPosition'], // only necessary
]);

// ❌ Wrong - everything
$response = $this->forwardToApi('GET', 'users', [
    'includes' => ['jobPosition', 'department', 'address', 'manager', ...],
]);
```

### 2. Correct pagination

```php
// ✅ Correct
public function index(Request $request): View
{
    $response = $this->forwardToApi('GET', 'users', [
        'per_page' => min($request->get('per_page', 15), 100),
    ]);

    return view('admin.users.index', ['users' => $response['data']]);
}

// ❌ Wrong - all records
$response = $this->forwardToApi('GET', 'users');
```

### 3. Caching for static data

```php
public function create(): View
{
    // If roles change infrequently
    $roles = cache()->remember('roles', now()->addHours(1), function () {
        $response = $this->forwardToApi('GET', 'roles');
        return $response['data'] ?? [];
    });

    return view('admin.users.create', ['roles' => $roles]);
}
```

---

## 10. Testing

### Unit Test

```php
public function test_user_index_calls_api(): void
{
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'web')
        ->get('/admin/users');

    $response->assertOk();
    $response->assertViewIs('admin.users.index');
}
```

### Mock API Response

```php
use Illuminate\Testing\Fluent\AssertableJson;

public function test_user_creation(): void
{
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'web')
        ->post('/admin/users', [
            'first_name' => 'John',
            'mobile' => '09123456789',
        ]);

    $response->assertRedirect(route('admin.users.index'));
}
```

---

## Summary Checklist

- [ ] All panel controllers inherit from `BaseWebController`
- [ ] No direct Eloquent calls
- [ ] All data comes from `forwardToApi`
- [ ] Validation is in two layers (panel + API)
- [ ] Appropriate error handling
- [ ] Authorization is managed by API
- [ ] Pagination is used
- [ ] Includes used for eager loading
- [ ] Response structure is correct
- [ ] Sufficient testing

---

## Complete Example (Reference)

```php
<?php

namespace App\Core\User\Http\Controllers\Web;

use Applications\Contracts\BaseWebController;use Illuminate\Http\RedirectResponse;use Illuminate\Http\Request;use Illuminate\View\View;

class UserManagementController extends BaseWebController
{
    public function index(Request $request): View
    {
        // تمام query params به API
        $response = $this->forwardToApi('GET', 'users', $request->all());

        return view('admin.users.index', [
            'users' => $response['data'] ?? [],
            'meta' => $response['meta'] ?? [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // validation پنل برای UX
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'mobile' => 'required|regex:/^09\d{9}$/',
        ]);

        // API validation + business rules
        $this->forwardToApi('POST', 'users', $validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created');
    }

    public function show(int $id): View
    {
        $response = $this->forwardToApi('GET', "users/{$id}", [
            'includes' => ['jobPosition', 'department'],
        ]);

        return view('admin.users.show', [
            'user' => $response['data'],
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
        ]);

        $this->forwardToApi('PATCH', "users/{$id}", $validated);

        return redirect()->route('admin.users.show', $id)
            ->with('success', 'User updated');
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->forwardToApi('DELETE', "users/{$id}");
        } catch (\Exception $e) {
            abort(403);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted');
    }
}
```
