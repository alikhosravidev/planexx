# Design Patterns

> **Source**: `.windsurf/rules/design-patterns.md`

## Core Principles

### 1. Follow Standard Implementations
Always refer to TutorialsPoint Design Patterns Guide. **Avoid creative variations**.

### 2. Preserve Interface Definitions
Interface definitions are **essential and non-optional**.

### 3. Avoid Creative Implementations
Stick to proven standard patterns. Save mental energy for business problems.

## Common Patterns Summary

### Creational
- **Factory Method** - Create objects via factory
- **Singleton** - Single instance
- **Builder** - Step-by-step construction

### Structural
- **Adapter** - Convert interface
- **Decorator** - Add behavior
- **Proxy** - Control access

### Behavioral
- **Observer** - Event notification
- **Strategy** - Interchangeable algorithms
- **Template Method** - Define skeleton

## Example: Factory Method
```php
interface Product {
    public function operation(): string;
}

interface Creator {
    public function factoryMethod(): Product;
}

class ConcreteCreator implements Creator {
    public function factoryMethod(): Product {
        return new ConcreteProductA();
    }
}
```

## CTO Guidance
> "Design patterns are standardized solutions. Following established implementations ensures code maintainability."

## Actionable Rule
Review design pattern implementations during code reviews.

## Full Details
`.windsurf/rules/design-patterns.md`

## Further Reading
- Design Patterns: Elements of Reusable Object-Oriented Software
- Refactoring.Guru Design Patterns
