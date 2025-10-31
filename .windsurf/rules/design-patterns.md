---
trigger: manual
---

# Design Patterns Standards and Best Practices for Laravel Projects

This document establishes our team's standards for implementing **design patterns** in Laravel backend projects.

## Core Principles

### 1. Follow Standard Implementations

Always refer to the TutorialsPoint Design Patterns Guide for standard implementations. **Avoid creative variations** to ensure consistency.

### 2. Preserve Interface Definitions

**Interface definitions** are essential and non-optional in pattern structures.

```php
// ❌ Incorrect: Non-standard interface
interface ProductCreator {
    public function makeProduct($type);
}

// ✅ Correct: Standard Factory Method interface
interface Creator {
    public function factoryMethod(): Product;
}

interface Product {
    public function operation(): string;
}
```

### 3. Avoid Creative Implementations

Stick to proven standard patterns to conserve mental energy for business problems.

## Common Design Patterns

### Creational Patterns

**Factory Method:**
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

**Singleton:**
```php
class Singleton {
    private static $instance = null;

    private function __construct() {}

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __clone() {}
    private function __wakeup() {}
}
```

**Builder:**
```php
interface Builder {
    public function reset(): void;
    public function buildPartA(): void;
    public function getProduct(): Product;
}

class ConcreteBuilder implements Builder {
    private $product;

    public function __construct() {
        $this->reset();
    }

    public function reset(): void {
        $this->product = new Product();
    }

    public function buildPartA(): void {
        $this->product->add("PartA");
    }

    public function getProduct(): Product {
        $result = $this->product;
        $this->reset();
        return $result;
    }
}
```

### Structural Patterns

**Adapter:**
```php
interface Target {
    public function request(): string;
}

class Adaptee {
    public function specificRequest(): string {
        return "Specific request";
    }
}

class Adapter implements Target {
    public function __construct(private Adaptee $adaptee) {}

    public function request(): string {
        return "Adapter: " . $this->adaptee->specificRequest();
    }
}
```

**Decorator:**
```php
interface Component {
    public function operation(): string;
}

abstract class Decorator implements Component {
    public function __construct(protected Component $component) {}
}

class ConcreteDecorator extends Decorator {
    public function operation(): string {
        return "Decorator(" . parent::operation() . ")";
    }
}
```

**Proxy:**
```php
interface Subject {
    public function request(): void;
}

class Proxy implements Subject {
    public function __construct(private RealSubject $realSubject) {}

    public function request(): void {
        $this->checkAccess();
        $this->realSubject->request();
    }

    private function checkAccess(): void {
        // Access control logic
    }
}
```

### Behavioral Patterns

**Observer:**
```php
interface Subject {
    public function attach(Observer $observer): void;
    public function notify(): void;
}

interface Observer {
    public function update(Subject $subject): void;
}

class ConcreteSubject implements Subject {
    private $observers = [];

    public function attach(Observer $observer): void {
        $this->observers[] = $observer;
    }

    public function notify(): void {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}
```

**Strategy:**
```php
interface Strategy {
    public function doAlgorithm(array $data): array;
}

class ConcreteStrategyA implements Strategy {
    public function doAlgorithm(array $data): array {
        sort($data);
        return $data;
    }
}
```

**Template Method:**
```php
abstract class AbstractClass {
    final public function templateMethod(): void {
        $this->baseOperation1();
        $this->requiredOperation1();
    }

    abstract protected function requiredOperation1(): void;

    protected function baseOperation1(): void {
        // Implementation
    }
}
```

## Summary

1. Follow standard implementations from official references.
2. Preserve **interface definitions** exactly as specified.
3. Avoid creative implementations.
4. Save mental energy for business problems.

**Team CTO Guidance:**

> "**Design patterns** are standardized solutions. Following established implementations ensures code maintainability."

**Actionable Rule:**
- Review **design pattern** implementations during code reviews.

**Further Reading:**
- Design Patterns: Elements of Reusable Object-Oriented Software
- Refactoring.Guru Design Patterns
