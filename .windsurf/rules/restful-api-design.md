---
trigger: manual
---

import Tabs from '@theme/Tabs';
import TabItem from '@theme/TabItem';

# RESTful API Design Principles

## Introduction

This document outlines core principles for **RESTful API design**. It addresses misconceptions, provides guidelines for resource modeling, and ensures business logic stays on the server for clean client-server separation.

## Core Concepts

### What is a Resource?

**What is a Resource?**

A resource is a **noun**, not a verb. It represents a concept or entity in our business domain that can be identified, named, addressed, and transferred.

**Good examples**: Cart, User, Payment, Product, Invoice, Subscription

**Poor examples**: QuickPay, VerifyPayment, AddItemToCart

A resource is not necessarily a database table. Resources exist in the API and business layer, while tables are implementation details in the data layer. Keep these concerns separate.

Business processes (like `QuickPay`) should not appear in URIs. They should be implemented as orchestration in the service layer, not exposed directly in the API.

### What is a Sub-resource?

A sub-resource is a resource whose existence only makes sense within the context of another resource. This represents a composition relationship.

**Examples**:
- A `Comment` without a `Post` is meaningless. Therefore, `Comment` is a sub-resource of `Post`.
- A `CartItem` cannot exist without a `Cart`. Therefore, `CartItem` is a sub-resource of `Cart`.
- A `Verification` for a `Payment` is meaningless without that payment. Therefore, `Verification` is a sub-resource of `Payment`.

This relationship is elegantly reflected in URIs:

```
GET /posts/{postId}/comments                // List all comments for a specific post
GET /carts/{cartId}/items                   // List all items in a specific cart
POST /payments/{paymentId}/verifications    // Create a verification for a specific payment
```

Sub-resources help organize complex domains and create clearer, more intuitive APIs. They also naturally enforce access control and data integrity constraints.

## Common Misconceptions

### "REST is Only for CRUD Operations"

This is one of the biggest misconceptions about REST. REST is an architectural style, not a protocol limited to four basic operations.

While CRUD operations naturally map to HTTP methods (POST, GET, PUT/PATCH, DELETE), REST can handle complex operations through proper resource modeling.

**Guiding principle**: Instead of thinking about the "verb", think about what "noun" that verb creates or what "noun's" state it changes.

## Resource Modeling for Complex Operations

### Example: Payment Verification

Instead of:
```
POST /payment/verify
```

Use:
```
POST /payments/{paymentId}/verifications
```

This creates an audit trail and adheres to REST principles.

## Case Studies

<Tabs>
  <TabItem value="payment" label="Payment Processing">

### Payment Processing

Replace verb-based endpoints like `v1/client/payment/quickpay` with resource-based ones:

- `POST /carts` then `POST /payments` for QuickPay
- `PATCH /payments/{paymentId}` for JustPay
- `POST /payments/{paymentId}/verifications` for Verify

  </TabItem>
  <TabItem value="cart" label="Cart Management">

### Cart Management

Use sub-resources:
```
POST /carts/{cartId}/items
DELETE /carts/{cartId}/items/{itemId}
```

  </TabItem>
  <TabItem value="plural-naming" label="Plural Resource Naming">

### Plural Resource Naming

Use plural names: `/pricing-plans`

  </TabItem>

## Architectural Considerations

### Server-Side Orchestration

Business processes should reside on the server, not the client.

### API Design Patterns

#### Resource-Based vs. Process-Based APIs

**Resource-Based (Pure REST):** Exposes stable resources, orchestration by client.

**Process-Based:** Exposes high-level endpoints, server handles orchestration.

Default to Resource-Based unless compelling reasons for Process-Based.

## Implementation Guidelines

### Naming Conventions
1. Use plural nouns for collection resources
2. Use concrete, domain-specific names
3. Use kebab-case for multi-word resources
4. Avoid verbs in URIs

### HTTP Methods
| Method | Purpose | Example |
|--------|---------|---------|
| GET | Retrieve resources | `GET /products` |
| POST | Create a new resource | `POST /orders` |
| PUT | Replace a resource | `PUT /users/123` |
| PATCH | Update partially | `PATCH /products/456` |
| DELETE | Remove a resource | `DELETE /carts/789` |

### Status Codes
| Code | Meaning | When to Use |
|------|---------|-------------|
| 200 | OK | Successful GET, PUT, PATCH, DELETE |
| 201 | Created | Successful POST |
| 204 | No Content | Operation with no response |
| 400 | Bad Request | Invalid input |
| 401 | Unauthorized | Auth required |
| 403 | Forbidden | Insufficient permissions |
| 404 | Not Found | Resource doesn't exist |
| 422 | Unprocessable | Semantic errors |

## Advanced Patterns

### HATEOAS
Include links in responses for related resources.

### Idempotency
Use for non-repeatable operations.

## Migration Strategy
1. Version your API
2. Incremental implementation
3. Deprecation plan
4. Documentation

## Decision Framework for API Design Approach

Checklist for Process-Based APIs:
1. **Atomicity Requirement**
2. **Client Complexity**
3. **Network Efficiency**
4. **Security Concerns**
5. **Client Capabilities**

If yes to any, Process-Based may be appropriate.

## Conclusion

Properly designed RESTful APIs lead to maintainable systems. Focus on clean resource modeling, server-side business logic, and client simplicity.
