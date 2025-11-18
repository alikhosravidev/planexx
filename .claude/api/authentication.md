# Authentication

> **Source**: `.windsurf/rules/authentication.md`

## API Key Authentication
All API endpoints require authentication via API key header:

```http
Api-Key: {{api-key}}
```

## Admin API
Requires API key with **administrative privileges** for management operations.

## Client API
Uses standard API keys for public/user interactions. Subject to rate limiting.

## Permissions
Admin endpoints require specific permissions based on user role.
Insufficient permissions → **403 Forbidden**

## Rate Limiting (Client API)
Rate limit info in response headers:
```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1625097600
```

## Authentication Strategies
The system uses multiple auth strategies via tagged services:
- **OTP** authentication (SMS-based)
- **Password** authentication
- Configured via `services.auth.otp.*` config

## Full Details
`.windsurf/rules/authentication.md`
