# Route Management با Ziggy

## 📋 مقدمه

سیستم Route Management ما از **Ziggy** استفاده می‌کند که routes Laravel رو به JavaScript ترجمه می‌کند. این باعث می‌شود که URL‌ها به صورت dynamic و درست تولید شوند.

## 🔧 Route Manager

Route Manager یک wrapper است که Ziggy رو مدیریت می‌کند و API routes رو تولید می‌کند.

### استفاده

```javascript
import { routeManager } from '@/core/route-manager.js';

// Get full URL
const fullUrl = routeManager.route('user.auth');
// Result: http://localhost:8000/auth

// Get API route URL (/api/v1)
const apiUrl = routeManager.apiRoute('user.auth');
// Result: /api/v1/auth

// Get admin API route URL (/api/v1/admin)
const adminUrl = routeManager.adminApiRoute('user.auth');
// Result: /api/v1/admin/auth
```

## 🌐 Routes موجود

### Authentication Routes

```javascript
// شروع احراز هویت (ارسال شماره موبایل)
routeManager.adminApiRoute('user.initiate.auth')
// GET /api/v1/admin/auth

// تایید احراز هویت (ارسال کد OTP)
routeManager.adminApiRoute('user.auth')
// POST /api/v1/admin/auth

// خروج از سیستم
routeManager.adminApiRoute('user.logout')
// GET /api/v1/admin/auth/logout

// درخواست بازیابی رمز
routeManager.adminApiRoute('user.initiate.resetPassword')
// GET /api/v1/admin/reset-password

// تایید تغییر رمز
routeManager.adminApiRoute('user.resetPassword')
// PUT /api/v1/admin/reset-password
```

### Web Routes

```javascript
// صفحه لاگین
routeManager.route('login')
// http://localhost:8000/login
```

## 📝 استفاده در Auth Module

Auth Module خودکار از Ziggy استفاده می‌کند:

```javascript
import { authModule } from '@/modules/auth.js';

// initiateAuth درون‌خود از route manager استفاده می‌کند
await authModule.initiateAuth('09123456789', 'otp');

// verifyAuth هم درون‌خود route رو محاسبه می‌کند
await authModule.verifyAuth('1234', metadata);

// logout هم automatic route رو تولید می‌کند
await authModule.logout();
```

## 🛠️ API Reference

### route(name, params)

دریافت full URL برای یک route

```javascript
routeManager.route('login');
// http://localhost:8000/login

routeManager.route('addresses.show', { address: 1 });
// http://localhost:8000/location/addresses/1
```

### apiRoute(name, params)

دریافت API route URL (با /api/v1 prefix)

```javascript
routeManager.apiRoute('user.auth');
// /api/v1/auth
```

### adminApiRoute(name, params)

دریافت Admin API route URL (با /api/v1/admin prefix)

```javascript
routeManager.adminApiRoute('user.auth');
// /api/v1/admin/auth
```

### has(name)

بررسی وجود route

```javascript
if (routeManager.has('user.auth')) {
  // route exists
}
```

### getAuthRoutes()

دریافت تمام auth routes

```javascript
const authRoutes = routeManager.getAuthRoutes();
// {
//   'user.initiate.auth': {...},
//   'user.auth': {...},
//   'user.logout': {...},
//   ...
// }
```

### getMethods(name)

دریافت HTTP methods برای یک route

```javascript
routeManager.getMethods('user.auth');
// ['POST']

routeManager.getMethods('user.initiate.auth');
// ['GET', 'HEAD']
```

### acceptsMethod(name, method)

بررسی قبول یک method برای route

```javascript
routeManager.acceptsMethod('user.auth', 'POST');
// true

routeManager.acceptsMethod('user.auth', 'GET');
// false
```

### filter(pattern)

دریافت routes matching pattern

```javascript
routeManager.filter('auth');
// تمام routes که "auth" رو دارند

routeManager.filter('admin');
// تمام routes شروع شده با "admin"
```

### all()

دریافت تمام routes

```javascript
const allRoutes = routeManager.all();
```

## 🔌 Integration با HTTP Client

HTTP Client خودکار از Ziggy routes استفاده می‌کند:

```javascript
import { httpClient } from '@/core/http-client.js';
import { routeManager } from '@/core/route-manager.js';

// Get route URL
const url = routeManager.adminApiRoute('user.auth');

// Use with HTTP Client
const response = await httpClient.post(url, data);
```

## 🚀 Best Practices

### ✅ صحیح

```javascript
// Use routeManager برای تولید URLs
const url = routeManager.adminApiRoute('user.auth');
await httpClient.post(url, data);

// یا در auth module (خودکار):
await authModule.initiateAuth(mobile);
```

### ❌ غلط

```javascript
// Hard-coded URLs (نه!)
await httpClient.post('/api/v1/admin/auth', data);

// Multiple baseURLs (نه!)
httpClient.baseURL = '/api/v1/admin';
await httpClient.post('/auth', data);
```

## 📊 Route Structure

```
routes.php (Laravel)
    ↓
php artisan route:list
    ↓
Ziggy (JavaScript generation)
    ↓
ziggy.js (Generated file)
    ↓
routeManager (Wrapper)
    ↓
authModule, httpClient (Usage)
```

## 🔄 Adding New Routes

### 1. Laravel میں route تعریف کریں

```php
Route::middleware(['api'])->group(function () {
    Route::post('/users', 'UserController@store')->name('user.create');
    Route::get('/users/{user}', 'UserController@show')->name('user.show');
});
```

### 2. Ziggy خودکار generate کرے (next build)

```javascript
// After running: php artisan ziggy:generate
```

### 3. JavaScript میں استفاده کریں

```javascript
const url = routeManager.apiRoute('user.create');
const response = await httpClient.post(url, userData);

const url2 = routeManager.apiRoute('user.show', { user: 1 });
const user = await httpClient.get(url2);
```

## 🧪 Testing Routes

```javascript
// Check if route exists
if (routeManager.has('user.auth')) {
  console.log('Route exists');
}

// Get all routes for debugging
console.log(routeManager.all());

// Check route methods
console.log(routeManager.getMethods('user.auth'));

// Filter routes
console.log(routeManager.filter('admin'));
```

## 🔐 Security Benefits

1. **No hard-coded URLs** - تمام URLs automatically تولید می‌شوند
2. **Type-safe** - اگر route تغییر کند، خودکار error می‌دهد
3. **Refactoring-friendly** - route تغییر کنید، همه جا automatic update می‌شود
4. **Parameter validation** - parameters خودکار validate می‌شوند

## ⚡ Performance

- Ziggy یک static file است (pre-generated)
- No runtime route generation
- No extra API calls برای routes
- Minimal overhead

## 🎓 Examples

### Example 1: دریافت صارفین

```javascript
// Laravel Route
Route::get('/users', 'UserController@index')->name('user.index');

// JavaScript Usage
const url = routeManager.apiRoute('user.index');
const users = await httpClient.get(url);
```

### Example 2: فائل اپ لوڈ کریں

```javascript
// Laravel Route
Route::post('/files/upload', 'FileController@store')->name('file.upload');

// JavaScript Usage
const url = routeManager.apiRoute('file.upload');
await httpClient.uploadFile(url, file);
```

### Example 3: Parametrized Route

```javascript
// Laravel Route
Route::put('/users/{user}', 'UserController@update')->name('user.update');

// JavaScript Usage
const url = routeManager.apiRoute('user.update', { user: 123 });
await httpClient.put(url, updatedData);
```

## 📚 مراجع

- [Ziggy Documentation](https://github.com/tighten/ziggy)
- `docs/javascript-architecture.md` - معماری عمومی
- `resources/js/core/route-manager.js` - کوڈ منبع
