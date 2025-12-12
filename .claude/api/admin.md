# Admin API

> **Source**: `.windsurf/rules/api-v1-admin.md`

## Overview
Admin-facing API endpoints designed for administrative applications. Require elevated permissions.

## Authentication
See: `.claude/api/authentication.md`

Uses Sanctum bearer tokens (`auth:api` / `auth:sanctum`) with administrative privileges.

## Permissions
Admin endpoints require specific permissions based on user role.
**403 Forbidden** returned for insufficient permissions.

## Namespace & Prefixes
- Base prefix: `/api/v1/admin`
- Organization module prefix: `/api/v1/admin/org`
- Middleware: `auth:sanctum` (same tokens as `auth:api`)

## Available Endpoints (Organization module)
- **Departments** (`Route::apiResource`)
  - `GET /api/v1/admin/org/departments`
  - `GET /api/v1/admin/org/departments/{id}`
  - `POST /api/v1/admin/org/departments`
  - `PUT/PATCH /api/v1/admin/org/departments/{id}`
  - `DELETE /api/v1/admin/org/departments/{id}`
- **Users** (`Route::apiResource`)
  - `GET|POST|PUT|PATCH|DELETE /api/v1/admin/org/users`
  - Sub-resource: `GET /api/v1/admin/org/users/{user}/roles`, `PUT /api/v1/admin/org/users/{user}/roles`
- **Roles** (`Route::apiResource`)
- **Permissions** (`index`, `show`, `keyValList`)
- **Addresses** (`Route::apiResource`)
- **Cities** (`index`, `show`)
- **Job Positions** (`Route::apiResource`)
- **Enums** (shared admin)
  - `GET /api/v1/admin/enums/{enum}`
  - `GET /api/v1/admin/enums/{enum}/key-value-list`

## Department Web → API Flow (مثال end-to-end)
1) **Web request**: کاربر صفحه مدیریت دپارتمان‌ها را باز می‌کند.
   - Controller: `DepartmentWebController@index`
   - Calls: `apiGet('api.v1.admin.org.departments.index', params...)`
2) **Route resolution**: نام روت به آدرس `/api/v1/admin/org/departments` نگاشت می‌شود و با توکن Sanctum ارسال می‌شود.
3) **API entry**: `DepartmentAPIController@index` (از `Route::apiResource`) درخواست را دریافت می‌کند.
   - پشتیبانی از پارامترهای عمومی فیلتر/جستجو/اینکلود/withCount از `BaseAPIController`.
4) **سرویس دامنه**: `DepartmentService` عملیات ایجاد/ویرایش/حذف یا واکشی را انجام می‌دهد.
5) **ترنسفورمر**: `DepartmentTransformer` خروجی را به ساختار API تبدیل می‌کند و پاسخ استاندارد از `ResponseBuilder` برمی‌گردد.
6) **بازگشت به وب**: پاسخ JSON شامل داده و متای صفحه‌بندی به کنترلر وب می‌رسد و در ویو `Organization::departments.*` رندر می‌شود.

## Common Query Parameters
See: `.claude/presentation/controllers.md` for full query parameter details.

## Example Usage

### List departments (web → api)
```http
GET /api/v1/admin/org/departments?filter[parent_id]=null&includes=children,thumbnail&withCount=users:employees_count,children.users:employees_count
Authorization: Bearer {{token}}
```

### Create department
```http
POST /api/v1/admin/org/departments
Authorization: Bearer {{token}}
Content-Type: multipart/form-data

name=R&D
type=DepartmentTypeEnum::Default
manager_id=123
parent_id=null
image=@/path/to/image.jpg
```

### Update department
```http
PUT /api/v1/admin/org/departments/{id}
Authorization: Bearer {{token}}
Content-Type: multipart/form-data

name=R&D Updated
```

### Enum key/value (for selects)
```http
GET /api/v1/admin/enums/DepartmentTypeEnum/key-value-list
Authorization: Bearer {{token}}
```

## Full Details
`.windsurf/rules/api-v1-admin.md`

## Related
- API Basics: `.claude/api/basics.md`
- Authentication: `.claude/api/authentication.md`
- Controllers: `.claude/presentation/controllers.md`
