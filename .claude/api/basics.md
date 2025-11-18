# API Basics

> **Source**: `.windsurf/rules/api-v1-basics.md`

## Response Format
All API responses follow standard format:

### Success
```json
{
  "status": true,
  "result": {
    // Response data
  },
  "meta": {
    // Metadata (pagination, etc.)
  }
}
```

### Error
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
| 401 | Unauthorized - Invalid/missing API key |
| 403 | Forbidden - Insufficient permissions |
| 404 | Not Found - Resource not found |
| 422 | Unprocessable Entity - Validation failed |
| 500 | Server Error |

## Common Query Parameters
See controller documentation: `.claude/presentation/controllers.md`

- Pagination: `per_page`, `page`
- Filtering: `filter[field]=value`, `search`
- Sorting: `order_by`, `order_direction`
- Relations: `includes`
- Field Selection: `field_sets`, `excludes`

## Full Details
`.windsurf/rules/api-v1-basics.md`
