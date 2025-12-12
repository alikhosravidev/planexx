# Authentication

> **Source**: `.windsurf/rules/authentication.md`

## Authentication Guard
All API routes use Sanctum bearer tokens. Guards in use:
- **`auth:api`** (Sanctum driver)
- **`auth:sanctum`** (alias, also Sanctum driver)

Both guards accept the same bearer tokens as configured in `config/auth.php`.

## Token Format
Authenticated requests must provide a Bearer token (Sanctum personal access token or session-based token issued by the application):

```http
Authorization: Bearer {{token}}
```

## Admin API
Requires a token belonging to a user with administrative privileges. Unauthorized/insufficient permission requests receive **401/403** responses.

## Client API
No client API routes are currently exposed in code. When added, they should also use Sanctum bearer tokens and may apply rate limiting as needed.

## Permissions
Admin endpoints require specific permissions based on user role.
Insufficient permissions → **403 Forbidden**

## Authentication Strategies
The system uses multiple auth strategies via tagged services:
- **OTP** authentication (SMS-based)
- **Password** authentication
- Configured via `services.auth.otp.*` config

## Full Details
`.windsurf/rules/authentication.md`
