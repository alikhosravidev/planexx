# Event Listeners

> **Source**: `.windsurf/rules/event-listeners.md`

## Principle: Event Listeners are Trade-offs
Balance decoupling and flexibility vs. readability and complexity.

## Critical Rules

### 1. Prohibit Intra-module Event Listeners
Event Listeners **within a single module** are **PROHIBITED**.

They should **only** facilitate communication between **separate modules**.

### 2. Prefer Event-Based for Inter-module Communication
```
| Scenario | Status | Impact |
|----------|--------|--------|
| Direct connection between entities of two modules | ❌ Prohibited | Violates modularity |
| Directly calling service from another module | ⚠️ Discouraged | Tight coupling |
| Event from Module A, listener in Module B | ✅ Allowed | Loose coupling |
```

### 3. Use Business Events
Fire **Business Events** for meaningful business occurrences, even without current listeners.

**Purpose**: Notifications, monitoring, auditing, logging, workflows.

```php
class OrderService {
    async completeOrder(orderId) {
        // Business logic
        eventBus.publish('order.completed', { orderId, timestamp });
    }
}
```

### 4. Asynchronous Inter-module Communication
Use when:
- ✅ Communicating between independent modules
- ✅ Decoupling more important than direct coupling
- ✅ Multiple components react to event

Avoid when:
- ❌ Intra-module communication
- ❌ Direct coupling simpler
- ❌ Synchronous needs

### 5. Event Listener as Orchestrator
Listeners should:
- Delegate business logic to service layers
- Avoid complex conditionals
- Minimize synchronous operations
- Focus on routing and coordination

## Refactoring Guidance
- Refactor intra-module events to direct calls
- Keep inter-module events for flexibility
- Document all business events and purposes

## Full Details
`.windsurf/rules/event-listeners.md`

## Related
- Observers: `.claude/infrastructure/observers.md`
- Services: `.claude/application/services.md`
