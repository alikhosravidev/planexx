---
trigger: manual
---

# API Basics

This document outlines the basic principles and common patterns used across the API v1.

## Authentication

See [Authentication](./authentication.md) for authentication details.

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

## Common Query Parameters

See [Common Parameters](./common-parameters.md) for details on pagination, filtering, including related resources, and field selection.
