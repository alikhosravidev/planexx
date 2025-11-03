---
trigger: manual
---

# Authentication

This document describes the authentication mechanism used across the API v1.

## API Key Authentication

All API endpoints require authentication using an **API key**. The API key must be included in the request headers as follows:

```http
Api-Key: {{api-key}}
```

### Admin API

Admin-facing endpoints require an API key with administrative privileges. These keys provide elevated access for management operations.

### Client API

Client-facing endpoints use standard API keys for public and user-specific interactions. Requests are subject to rate limiting.

## Permissions

Admin API endpoints require specific permissions based on the user's role. Insufficient permissions result in a **403 Forbidden** response.

## Rate Limiting (Client API)

Client API requests are rate-limited for system stability. Rate limit information is provided in response headers:

```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1625097600
```
