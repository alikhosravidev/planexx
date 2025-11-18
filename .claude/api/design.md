# RESTful API Design

> **Source**: `.windsurf/rules/restful-api-design.md`

## Core Concept: Resources are Nouns
**Resources** represent entities (nouns), not actions (verbs).

✅ Good: Cart, User, Payment, Product
❌ Poor: QuickPay, VerifyPayment, AddItemToCart

## Common Misconception
**"REST is only for CRUD"** - FALSE!

REST handles complex operations through **proper resource modeling**.

**Principle**: Think "what noun does this verb create?" not "what verb to use?"

## Sub-resources
Resources that only make sense within another resource's context:

```
POST /posts/{postId}/comments
POST /carts/{cartId}/items
POST /payments/{paymentId}/verifications
```

## Resource Modeling Examples

### ❌ Verb-based (Wrong)
```
POST /payment/verify
POST /payment/quickpay
POST /cart/add-item
```

### ✅ Resource-based (Correct)
```
POST /payments/{paymentId}/verifications
POST /carts (then) POST /payments
POST /carts/{cartId}/items
```

## HTTP Methods
| Method | Purpose | Example |
|--------|---------|---------|
| GET | Retrieve | `GET /products` |
| POST | Create | `POST /orders` |
| PUT | Replace | `PUT /users/123` |
| PATCH | Update partially | `PATCH /products/456` |
| DELETE | Remove | `DELETE /carts/789` |

## Naming Conventions
1. Use plural nouns for collections
2. Use concrete, domain-specific names
3. Use kebab-case for multi-word resources
4. Avoid verbs in URIs

## Status Codes
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

## Server-Side Orchestration
Business processes should reside on the **server**, not client.

Complex workflows (QuickPay, JustPay) are implemented in **service layer**, not exposed as API endpoints.

## API Design Patterns

### Resource-Based (Default)
- Exposes stable resources
- Client handles orchestration
- Maximum flexibility

### Process-Based (When Needed)
- Exposes high-level endpoints
- Server handles orchestration
- Use when: atomicity required, complex client logic, security concerns

## Full Details
`.windsurf/rules/restful-api-design.md`

## Related
- Controllers: `.claude/presentation/controllers.md`
- Services: `.claude/application/services.md`
