# Testing Guide - Authentication System

## 🧪 Manual Testing

### Step 1: Access Login Page
```
URL: http://localhost:8000/login
```

### Step 2: Browser Console Tests

اگر کہیں 404 آئے، یہ check کریں:

```javascript
// Check 1: Routes are defined
window.__CORE__.routeManager.has('user.auth')
// Expected: true ✅

// Check 2: Route URL generation
window.__CORE__.routeManager.adminApiRoute('user.auth')
// Expected: /api/v1/admin/auth ✅

// Check 3: API Manager config
window.__CORE__.apiManager.config.baseURL
// Expected: / (root only) ✅

// Check 4: Full request URL
const route = window.__CORE__.routeManager.adminApiRoute('user.initiate.auth');
console.log('Request will go to:', route);
// Expected: /api/v1/admin/auth ✅
```

### Step 3: Test OTP Flow

```
1. شماره موبایل: 09123456789
2. دکمه "دریافت کد تایید" کلیک کنید
3. Check Network tab → شامل طلب URL:
   - Request URL: http://localhost:8000/api/v1/admin/auth?identifier=...
   - Status: 200 ✅ (یا درست error message)

4. کد OTP: 1111
5. خودکار submit شود
6. موفقیت یا خطای واضح
```

---

## 🔍 Network Debugging

### Chrome DevTools (F12):

#### Network Tab:
1. Open Network tab
2. Clear history (بھاگ delete)
3. Click "دریافت کد تایید"
4. Look for request to `/api/v1/admin/auth`
   - ✅ Should be: `GET /api/v1/admin/auth?identifier=09123456789&authType=otp`
   - ❌ NOT: `GET /api/v1/api/v1/admin/auth` (double baseURL)
   - ❌ NOT: `GET /auth` (missing prefix)

#### Console Tab:
```javascript
// Debug current request
window.__CORE__.apiManager.pending
// Shows pending requests

// Check for errors
window.__CORE__.stateManager.export()
// Show current state
```

---

## ✅ Verification Checklist

### Before Report Issue, Check:

- [ ] Build was successful: `npm run build` ✅
- [ ] Browser console has no errors (F12 → Console)
- [ ] Check route generation:
  ```javascript
  window.__CORE__.routeManager.adminApiRoute('user.auth')
  ```
- [ ] Check baseURL:
  ```javascript
  window.__CORE__.apiManager.config.baseURL === '/'
  ```
- [ ] Check Ziggy routes loaded:
  ```javascript
  window.__CORE__.routeManager.has('user.auth')
  ```
- [ ] Network request shows correct URL (not doubled)

---

## 🐛 Common Issues & Solutions

### Issue: 404 - /api/v1/api/v1/admin/auth

**Cause**: baseURL is set to `/api/v1` somewhere

**Fix**:
```javascript
// Check this
window.__CORE__.apiManager.config.baseURL
// Should be: '/' not '/api/v1'
```

**Solution**: Restart app after rebuild

### Issue: 404 - /auth (no prefix)

**Cause**: Route not generating prefix

**Fix**:
```javascript
window.__CORE__.routeManager.adminApiRoute('user.auth')
// Should return: /api/v1/admin/auth
```

### Issue: CORS Error

**Cause**: Browser security

**Check**: Does Laravel have CORS configured?

### Issue: Network shows 404 but different URL

**Solution**:
1. Hard refresh: `Ctrl+Shift+R` or `Cmd+Shift+R`
2. Clear browser cache
3. `npm run build` again
4. Restart dev server

---

## 🧬 Code Review Checklist

### auth.js Module:
```javascript
// Should use routeManager (not hardcoded URLs)
const route = routeManager.adminApiRoute(this.config.routes.initiate);
await httpClient.get(route, { params: { ... } });
```

### core/index.js:
```javascript
// baseURL should be root
apiManager.init({
  baseURL: '/',  // ✅ NOT /api/v1
  ...
});
```

### pages/auth.js:
```javascript
// Should NOT have hardcoded baseURL
core.init({
  // baseURL: '/api/v1/admin',  // ❌ Remove if present
  debug: true,
  ...
});
```

---

## 📊 Expected Request Pattern

```
Flow: User enters mobile → Click button → API request

1. routeManager.adminApiRoute('user.initiate.auth')
   ↓
   Returns: /api/v1/admin/auth

2. httpClient.get('/api/v1/admin/auth', { params: { ... } })
   ↓
   apiManager.get('/api/v1/admin/auth', { ... })
   ↓
   axios.get('/api/v1/admin/auth', { baseURL: '/' })
   ↓
   Final URL: http://localhost:8000/api/v1/admin/auth ✅
```

---

## 🎯 Expected Status Codes

| Request | Method | Expected | Error |
|---------|--------|----------|-------|
| Initiate Auth | GET | 200 | 429 (rate limit), 422 (validation) |
| Verify Auth | POST | 200 | 401 (invalid), 429 (rate limit) |
| Logout | GET | 200 | N/A |
| Reset Password | GET | 200 | 404 (not found) |

---

## 📝 Logging

### Enable detailed logging:

```javascript
// In browser console:
window.__CORE__.eventBus.on('*', (payload) => {
  console.log('Event fired:', payload);
});

// Watch API requests:
window.__CORE__.eventBus.on('api:request:start', (data) => {
  console.log('API Request:', data.url);
});

window.__CORE__.eventBus.on('api:request:error', (data) => {
  console.error('API Error:', data);
});
```

---

## ✨ If Everything Works

After successful test, the flow should be:

```
✓ Phone number entered
✓ OTP sent (SMS/notification)
✓ OTP verified
✓ Token stored
✓ Redirect to dashboard
✓ User logged in
```

---

## 📞 Reporting Issues

اگر problem ہے تو یہ information دیں:

1. **Request URL** (from Network tab)
   - ہے کیا URL expected ہے؟
2. **Response Status**
   - 404, 500, etc?
3. **Response Body**
   - Error message کیا ہے?
4. **Console Errors**
   - JavaScript errors ہیں؟
5. **Test Results**:
   ```javascript
   window.__CORE__.routeManager.adminApiRoute('user.auth')
   window.__CORE__.apiManager.config.baseURL
   ```

---

## 📚 Reference

- `docs/javascript-architecture.md` - Full architecture
- `docs/route-management.md` - Route management
- `resources/js/core/` - Core modules
- `resources/js/modules/auth.js` - Auth logic
