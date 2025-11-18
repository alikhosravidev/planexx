# Client API

> **Source**: `.windsurf/rules/api-v1-client.md`

## Overview
Client-facing API endpoints designed for public applications and client interactions.

## Authentication
See: `.claude/api/authentication.md`

Uses standard API keys. **Subject to rate limiting**.

## Available Modules

### User Module
User-related features:
- **Addresses** - Retrieve and manage user addresses
- **Cities** - Retrieve city information
- **Location** - General location-related endpoints

## Common Query Parameters
See: `.claude/presentation/controllers.md` for full query parameter details.

## Example Usage

### Basic Request
```http
GET /api/client/v1/location/cities
Api-Key: {{api-key}}
```

### Filtering Cities
```http
GET /api/client/v1/location/cities?search=name:Tehran&searchFields=name&searchJoin=and
Api-Key: {{api-key}}
```

### Pagination
```http
GET /api/client/v1/location/cities?per_page=10&page=2
Api-Key: {{api-key}}
```

## Rate Limiting
Client API requests are rate-limited. Rate limit info in response headers:
```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1625097600
```

## Full Details
`.windsurf/rules/api-v1-client.md`

## Related
- API Basics: `.claude/api/basics.md`
- Authentication: `.claude/api/authentication.md`
- Controllers: `.claude/presentation/controllers.md`
