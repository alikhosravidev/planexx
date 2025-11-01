---
trigger: manual
---

# Admin API Overview

This document provides an overview of the admin-facing API endpoints available in v1. These endpoints are designed for administrative applications and require elevated permissions.

## Authentication

Admin API endpoints require authentication using an **API key** with administrative privileges. The **API key** should be included in the request headers:

```http
Api-Key: {{admin-api-key}}
```

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

Most Admin API endpoints support the following common query parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| includes | string | Related entities to include in the response |
| field_sets | object | JSON object specifying which fields to include for each entity type |
| excludes | string | Fields to exclude from the response |
| search | string | Search terms with format `field:value` |
| searchFields | string | Fields to search in |
| searchJoin | string | How to join multiple search terms (`and` or `or`) |
| per_page | integer | Number of items per page (default: 15) |
| page | integer | Page number for pagination (default: 1) |
| order_by | string | Field to order results by |
| order_direction | string | Direction to order results (`asc` or `desc`) |

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
