# Admin API

> **Source**: `.windsurf/rules/api-v1-admin.md`

## Overview
Admin-facing API endpoints designed for administrative applications. Require elevated permissions.

## Authentication
See: `.claude/api/authentication.md`

Admin API requires **API keys with administrative privileges**.

## Permissions
Admin endpoints require specific permissions based on user role.
**403 Forbidden** returned for insufficient permissions.

## Available Modules

### User Module
Administrative access to user management:
- **Addresses** - Create, update, delete, manage user addresses
- **Cities** - Manage city information (read-only)
- **Location** - General location-related endpoints

## Common Query Parameters
See: `.claude/presentation/controllers.md` for full query parameter details.

## Example Usage

### Basic Request
```http
GET /api/admin/v1/location/addresses
Api-Key: {{admin-api-key}}
```

### Creating a New Address
```http
POST /api/admin/v1/location/addresses
Api-Key: {{admin-api-key}}
Content-Type: application/json

{
  "receiver_name": "John Doe",
  "postal_code": "12345",
  "city_id": 1
}
```

### Updating an Address
```http
PUT /api/admin/v1/location/addresses/1
Api-Key: {{admin-api-key}}
Content-Type: application/json

{
  "receiver_name": "Jane Doe"
}
```

### Deleting an Address
```http
DELETE /api/admin/v1/location/addresses/1
Api-Key: {{admin-api-key}}
```

## Full Details
`.windsurf/rules/api-v1-admin.md`

## Related
- API Basics: `.claude/api/basics.md`
- Authentication: `.claude/api/authentication.md`
- Controllers: `.claude/presentation/controllers.md`
