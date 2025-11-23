# معماری JavaScript - سیستم مدیریت درخواست‌های API

## 📚 مقدمه

این document توضیح می‌دهد که چگونه معماری JavaScript جامع و قابل استفاده مجدد برای سیستم Planexx ایجاد شده است. این معماری برای استفاده در تمام قسمت‌های پروژه طراحی شده و نه تنها برای login page.

## 🏗️ معماری کلی

```
resources/js/
├── core/                          # هسته سیستم
│   ├── index.js                   # نقطه ورود مرکزی
│   ├── api-manager.js             # مدیریت درخواست‌های HTTP
│   ├── error-handler.js           # مدیریت خطاها
│   ├── state-manager.js           # مدیریت state
│   ├── event-bus.js               # سیستم رویداد‌ها
│   └── http-client.js             # HTTP client سطح بالا
├── modules/                       # ماژول‌های قابل استفاده مجدد
│   ├── auth.js                    # ماژول احراز هویت
│   ├── form-handler.js            # مدیریت فرم‌ها
│   └── otp-auth-ui.js             # رابط کاربری OTP
├── pages/                         # صفحات specific
│   └── auth.js                    # صفحه احراز هویت
├── app.js                         # فایل اصلی اپلیکیشن
└── bootstrap.js                   # راه‌اندازی Axios و دیگر dependencies
```

## 🔌 Core Modules

### 1. API Manager

**مسئولیت**: مدیریت تمام درخواست‌های HTTP

```javascript
import { apiManager } from '@/core/api-manager.js';

// درخواست GET
const users = await apiManager.get('/users');

// درخواست POST
const newUser = await apiManager.post('/users', {
  name: 'John',
  email: 'john@example.com'
});

// Upload فایل
await apiManager.upload('/files/upload', file, {
  title: 'My File'
});

// Batch requests
const results = await apiManager.batch([
  { method: 'GET', url: '/users' },
  { method: 'GET', url: '/posts' },
]);

// Retry with backoff
const data = await apiManager.retry(
  () => apiManager.get('/data'),
  3,  // retries
  1000 // delay in ms
);
```

**ویژگی‌های اصلی**:
- ✅ Request/Response interceptors
- ✅ Caching برای GET requests
- ✅ Deduplication درخواست‌های تکراری
- ✅ Request timeout
- ✅ Progress tracking برای uploads
- ✅ Batch requests

### 2. Error Handler

**مسئولیت**: مدیریت تمام خطاها

```javascript
import { errorHandler } from '@/core/error-handler.js';

// معالجه دستی خطا
try {
  await apiManager.get('/users');
} catch (error) {
  errorHandler.handle(error, {
    showNotification: true,
    defaultMessage: 'Custom error message',
    handler: (err, msg) => console.log(msg)
  });
}

// Custom handler برای status code
errorHandler.registerHandler(404, (error, message) => {
  console.log('Resource not found:', message);
});

// Custom message برای endpoint
errorHandler.registerCustomMessage('/api/auth', (error) => {
  if (error.response?.status === 401) {
    return 'Invalid credentials';
  }
});

// Validation errors
if (errorHandler.isValidationError(error)) {
  const errors = errorHandler.getValidationErrors(error);
  // { field1: ['message1'], field2: ['message2'] }
}
```

**Types خطاها**:
- `network`: مشکل اتصال
- `timeout`: درخواست منقضی شد
- `auth`: خطای احراز هویت
- `validation`: خطای اعتبار سنجی
- `ratelimit`: درخواست‌های زیاد
- `server`: خطای سرور

### 3. State Manager

**مسئولیت**: مدیریت state اپلیکیشن

```javascript
import { stateManager } from '@/core/state-manager.js';

// Set state
stateManager.set('user', { id: 1, name: 'John' });

// Get state
const user = stateManager.get('user');

// Nested state
stateManager.set('user.profile.name', 'Jane');
const name = stateManager.get('user.profile.name');

// Watch for changes
const unwatch = stateManager.watch('user', (newValue, oldValue) => {
  console.log('User changed:', newValue);
});

// Computed properties
stateManager.defineComputed('isLoggedIn', (state) => {
  return !!state.authToken;
});

// Batch updates
stateManager.batch({
  user: { id: 1 },
  authToken: 'token123',
  isLoggedIn: true
});

// Persistence
stateManager.persist(['authToken', 'user']);

// Namespace
const userState = stateManager.createNamespace('user', {
  profile: {},
  preferences: {}
});

userState.set('profile.name', 'John');
```

**ویژگی‌ها**:
- ✅ Nested state access
- ✅ Watchers/Observers
- ✅ Computed properties
- ✅ Persistence to localStorage
- ✅ Namespaces برای isolation
- ✅ Batch updates

### 4. Event Bus

**مسئولیت**: سیستم Pub/Sub برای ارتباط components

```javascript
import { eventBus } from '@/core/event-bus.js';

// Listen to event
eventBus.on('user:login', (data) => {
  console.log('User logged in:', data);
});

// Listen once
eventBus.once('modal:closed', () => {
  console.log('Modal was closed');
});

// Emit event
eventBus.emit('user:login', { id: 1, name: 'John' });

// Priority listeners
eventBus.onPriority('api:error', handler, 10); // Higher priority

// Wait for event
const data = await eventBus.waitFor('user:created', 5000); // 5s timeout

// Namespaced events
const userEvents = eventBus.createNamespace('user');
userEvents.on('login', handler); // Listens to 'user:login'

// Middleware
eventBus.use((eventName, payload) => {
  // Transform payload
  return { ...payload, timestamp: Date.now() };
});

// Event history
const history = eventBus.getHistory();
const loginHistory = eventBus.getHistory('user:login');
```

**Use Cases**:
- Component communication
- Event-driven architecture
- Decoupled modules
- Debugging and monitoring

### 5. HTTP Client

**مسئولیت**: سطح بالای HTTP operations

```javascript
import { httpClient } from '@/core/http-client.js';

// CRUD operations
const user = await httpClient.getOne('/users', 1);
const users = await httpClient.get('/users');
const created = await httpClient.create('/users', data);
const updated = await httpClient.update('/users', 1, data);
const deleted = await httpClient.delete('/users', 1);

// Pagination
const page = await httpClient.fetchPaginated('/users', 1, 15);

// Filtering
const filtered = await httpClient.fetchFiltered('/users', {
  status: 'active',
  role: 'admin'
});

// Sorting
const sorted = await httpClient.fetchSorted('/users', 'created_at', 'desc');

// Search
const results = await httpClient.search('/users', 'john');

// File upload
await httpClient.uploadFile('/files', file, {
  additionalData: { category: 'documents' },
  onProgress: (percent) => console.log(percent + '%')
});

// Response transformers
httpClient.registerTransformer('/users', (response) => {
  return response.map(user => ({
    ...user,
    fullName: user.first_name + ' ' + user.last_name
  }));
});
```

## 📦 Modules

### Auth Module

```javascript
import { authModule } from '@/modules/auth.js';

// Init
authModule.init({
  baseURL: '/api/v1/admin',
  endpoints: {
    initiate: 'auth',
    verify: 'auth',
    logout: 'auth/logout'
  }
});

// Initiate auth (send OTP)
await authModule.initiateAuth('09123456789', 'otp');

// Verify code
await authModule.verifyAuth('1234', {
  fingerprint: 'device-fingerprint',
  userAgent: navigator.userAgent
});

// Check auth status
if (authModule.isAuthenticated()) {
  console.log('User:', authModule.getUser());
}

// Logout
await authModule.logout();

// Watch for auth changes
authModule.watchAuth((state) => {
  console.log('Auth state changed:', state);
});
```

### Form Handler

```javascript
import { formHandler } from '@/modules/form-handler.js';

// Create form instance
const form = formHandler.createForm('#login-form', {
  validateOnChange: true,
  validateOnBlur: true,
  onSubmit: async (values) => {
    // Handle submission
    return await apiManager.post('/login', values);
  },
  onSuccess: (result) => {
    console.log('Form submitted:', result);
  },
  onError: (error) => {
    console.error('Form error:', error);
  }
});

// Validate form
const isValid = form.validate();

// Get/Set values
form.setFieldValue('email', 'user@example.com');
const email = form.getFieldValue('email');
const allValues = form.getValues();

// Field errors
form.setFieldError('email', 'Invalid email');
const error = form.getFieldError('email');

// Reset/Clear
form.reset(); // Reset to initial values
form.clear(); // Clear all values

// Form state
console.log(form.isDirty); // Has user made changes?
console.log(form.isSubmitting); // Is currently submitting?

// Validation rules
formHandler.registerValidator('uniqueEmail', async (value) => {
  const exists = await checkEmailExists(value);
  return !exists;
});
```

### OTP Auth UI

```javascript
import { otpAuthUI } from '@/modules/otp-auth-ui.js';

// Init
otpAuthUI.init({
  auth: { baseURL: '/api/v1/admin' },
  mobileStepSelector: '#step-mobile',
  otpStepSelector: '#step-otp',
  otpLength: 4,
  resendTimeout: 60,
  redirectUrl: '/dashboard',
  onLoginSuccess: (data) => {
    // Custom callback
  }
});

// UI is automatically initialized
// Just use the HTML elements with the right IDs
```

## 🔌 Integration

### با محل استفاده (صفحه Login)

```javascript
// resources/js/pages/auth.js
import { core } from '@/core/index.js';
import { otpAuthUI } from '@/modules/otp-auth-ui.js';

// Initialize all core modules
core.init({
  baseURL: '/api/v1/admin',
  debug: true,
  initialState: {
    authToken: localStorage.getItem('auth_token'),
    user: JSON.parse(localStorage.getItem('user_data'))
  }
});

// Initialize UI
otpAuthUI.init();
```

### Custom Modules

```javascript
// ساخت ماژول جدید
import { httpClient } from '@/core/http-client.js';
import { eventBus } from '@/core/event-bus.js';

export const userModule = {
  async getProfile() {
    const user = await httpClient.getOne('/users', 'me');
    eventBus.emit('user:profile:loaded', user);
    return user;
  },

  async updateProfile(data) {
    const updated = await httpClient.update('/users', 'me', data);
    eventBus.emit('user:profile:updated', updated);
    return updated;
  }
};
```

## 🎯 Best Practices

### 1. استفاده از HTTP Client به جای API Manager

```javascript
// ✅ Good - استفاده از HTTP client
const users = await httpClient.get('/users');

// ❌ Avoid - استفاده مستقیم API Manager
const users = await apiManager.get('/users');
```

### 2. Error Handling

```javascript
// ✅ Good
try {
  const user = await httpClient.getOne('/users', id);
} catch (error) {
  // Error is already handled by error handler
  // Just do additional logic if needed
}

// ❌ Avoid - Double handling
try {
  const user = await httpClient.getOne('/users', id);
} catch (error) {
  errorHandler.handle(error); // Already done!
}
```

### 3. State Management

```javascript
// ✅ Good - استفاده از state برای shared data
stateManager.set('user', userData);
const user = stateManager.get('user');

// ❌ Avoid - استفاده از global variables
window.currentUser = userData;
```

### 4. Event Communication

```javascript
// ✅ Good - استفاده از events
eventBus.emit('user:updated', newData);

// ❌ Avoid - Direct function calls
updateUI(newData);
```

## 🧪 Testing

```javascript
// Test auth flow
describe('Auth Flow', () => {
  it('should authenticate user', async () => {
    await authModule.initiateAuth('09123456789', 'otp');
    await authModule.verifyAuth('1234');
    expect(authModule.isAuthenticated()).toBe(true);
  });

  it('should handle errors', async () => {
    try {
      await authModule.verifyAuth('wrong');
    } catch (error) {
      expect(error).toBeDefined();
    }
  });
});

// Test form submission
describe('Form Handler', () => {
  it('should validate and submit form', async () => {
    const form = formHandler.createForm('#test-form', {
      onSubmit: jest.fn().mockResolvedValue({ success: true })
    });

    form.setFieldValue('email', 'test@example.com');
    await form.submit();

    expect(form.onSubmit).toHaveBeenCalled();
  });
});
```

## 📊 Debugging

```javascript
// Access core modules from console
window.__CORE__.apiManager
window.__CORE__.stateManager
window.__CORE__.eventBus
window.__CORE__.errorHandler

// Export state
const state = stateManager.export();

// Get statistics
const stats = eventBus.getStats();

// Get pending requests
const pending = apiManager.pending;

// Get cached data
const cache = apiManager.cache;
```

## 🔄 Workflow

### Login Flow

```
1. User enters mobile number
   ↓
2. authModule.initiateAuth(mobile)
   ↓ (Event: auth:initiate:success)
3. Show OTP step
   ↓
4. User enters OTP
   ↓ (Auto-submit after 4 digits)
5. authModule.verifyAuth(code)
   ↓ (Event: auth:verify:success)
6. Save token and user data
   ↓
7. Redirect to dashboard
```

### API Request Flow

```
httpClient.get('/users')
  ↓
apiManager.get()
  ↓
Request Interceptors (add auth header)
  ↓
axios() - actual request
  ↓
Response Interceptors (transform data)
  ↓
Return data / Throw error
  ↓
Error Handler (handle if error)
```

## 📝 Checklists

### برای ایجاد صفحه جدید

- [ ] ایجاد module برای logic
- [ ] استفاده از httpClient برای API calls
- [ ] استفاده از eventBus برای ارتباط
- [ ] استفاده از stateManager برای shared state
- [ ] Setup error handlers
- [ ] Test all flows

### برای API Integration

- [ ] Create http requests using httpClient
- [ ] Handle errors properly
- [ ] Add loading states
- [ ] Show user feedback
- [ ] Cache responses if needed

## 🚀 مزایا

1. **قابلیت استفاده مجدد**: ماژول‌ها را می‌توانید در هر جای پروژه استفاده کنید
2. **Decoupling**: قسمت‌های مختلف به یکدیگر وابسته نیستند
3. **آسان Maintenance**: تغییرات در ماژول واحد تاثیر دیگر قسمت‌ها را ندارد
4. **Testability**: هر ماژول را به صورت جدا می‌توانید test کنید
5. **Scalability**: می‌توانید ماژول‌های جدید اضافه کنید بدون تغییر موجود
6. **Performance**: Caching، Deduplication و Lazy Loading
7. **DX بهتر**: Clean API و خوب documented

## 📞 مثال‌های بیشتر

برای مثال‌های بیشتر به فایل‌های زیر مراجعه کنید:

- `resources/js/pages/auth.js` - صفحه login
- `resources/js/modules/auth.js` - auth logic
- `resources/js/core/` - core modules

