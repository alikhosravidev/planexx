---
trigger: manual
---

# Client API Overview

This document provides an overview of the client-facing API endpoints available in v1. These endpoints are designed for public-facing applications and client applications that interact with the platform.

## Authentication

See [API Basics](./api-v1-basics.md) for authentication details.

## Available Modules

The **Client API** is organized into the following modules:

### User Module

The **User module** provides access to user-related features:

- Addresses - Retrieve and manage user addresses
- Cities - Retrieve city information
- Location - General location-related endpoints

## Rate Limiting

Client API requests are subject to **rate limiting** to ensure fair usage and system stability. **Rate limit** information is included in the response headers:

```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1625097600
```

## Common Query Parameters

See [API Basics](./api-v1-basics.md) for common query parameters including **pagination**, **filtering**, and **field selection**.

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
