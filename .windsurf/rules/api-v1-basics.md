---
trigger: manual
---

# API Basics

This document outlines the basic principles and common patterns used across the API v1.

## API Architecture

Controllers in this API do not contain business logic. Instead, they delegate to services for handling the core logic and interact with repositories for data access. This ensures separation of concerns and maintainability.

For example, a controller method might call a service method to perform an operation, then return the response.

## Authentication

All API endpoints require authentication using an **API key**. The **API key** should be included in the request headers.
Api-Key: {{api-key}}
```

## Response Format

All API responses follow a standard format:

```json
{
  "status": true,
  "result": {
    // Response data
  },
  "meta": {
    // Metadata such as pagination
  }
}
```

### Error Responses

When an error occurs, the response will have the following format:

```json
{
  "status": false,
  "error": {
    "code": "error_code",
  "message": "Error message",
    "details": {
      // Additional error details
    }
  }
}
```

## Common Status Codes

| Status Code | Description |
|-------------|-------------|
| 200 | Success |
| 400 | Bad Request - Invalid parameters |
| 401 | Unauthorized - Invalid or missing API key |
| 403 | Forbidden - Insufficient permissions |
| 404 | Not Found - Resource not found |
| 422 | Unprocessable Entity - Validation failed |
| 500 | Server Error |

## Pagination

Most endpoints that return lists of resources support **pagination** using the following query parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| per_page | integer | Number of items per page (default: 15) |
| page | integer | Page number (default: 1) |

Pagination information is included in the `meta.pagination` section of the response:

```json
{
  "meta": {
    "pagination": {
      "total": 100,
      "count": 15,
    "per_page": 15,
      "current_page": 1,
      "total_pages": 7,
      "links": {
        "next": "https://example.com/api/v3/resource?page=2"
      }
    }
  }
}
```

## Filtering

Most list endpoints support **filtering** using the following query parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| search | string | Search terms with format `field:value` |
| searchFields | string | Fields to search in |
| searchJoin | string | How to join multiple search terms (`and` or `or`) |

### Example

```http
GET /api/v1/resource?search=status:active&searchFields=status&searchJoin=and
```

## Including Related Resources

Many endpoints support including related resources using the **includes** parameter:

```http
GET /api/v1/resource?includes=relatedResource
```

## Field Selection

You can specify which fields to include in the response using the **field_sets** parameter:

```http
GET /api/v1/resource?field_sets={"resource":["id","name","status"]}
```
