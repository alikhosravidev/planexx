---
trigger: manual
---

# Admin API Overview

This document provides an overview of the admin-facing API endpoints available in v1. These endpoints are designed for administrative applications and require elevated permissions.

## Authentication

See [Authentication](./authentication.md) for authentication details. Admin API requires API keys with administrative privileges.

## Available Modules

The Admin API is organized into the following modules:

### User Module

The **User module** provides administrative access to user management features:

- Addresses - Create, update, delete, and manage user addresses
- Cities - Manage city information (read-only)
- Location - General location-related endpoints

## Permissions

Admin API endpoints require specific **permissions** based on the user's role. If a user does not have the required **permissions**, a **403 Forbidden** response will be returned.

## Common Query Parameters

See [Common Parameters](./common-parameters.md) for details on supported query parameters including pagination, filtering, includes, and field selection.

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
