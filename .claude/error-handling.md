# Error Handling & Try-Catch

> **Source**: `.windsurf/rules/error-handling.md`

## Core Principles

### 1. No Nested Try-Catch (Strictly Prohibited)
```php
// ❌ Prohibited
try {
    try { } catch (SomeException $e) { }
} catch (AnotherException $e) { }

// ✅ Better - Separate methods
public function performOperation(): Result {
    try {
        $data = $this->fetchData();
        return $this->processData($data);
    } catch (CustomException $e) {
        $this->logger->error('Operation failed', ['exception' => $e]);
        return new Result(false, $e->getMessage());
    }
}
```

### 2. Catch Specific Exceptions Only
```php
// ❌ Avoid
try { } catch (Exception $e) { }

// ✅ Better
try { } catch (UserNotFoundException $e) { }
```

### 3. Use Custom Exceptions
Inherit from base project exceptions.

### 4. When to Use Try-Catch
**Only for external systems**: APIs, files, databases, networks.

### 5. When NOT to Use Try-Catch
**Not for business rules** - Use "Safe Field" approach:
```php
// ❌ Avoid
try {
    if ($from->getBalance() < $amount) {
        throw new InsufficientFundsException();
    }
} catch (InsufficientFundsException $e) { }

// ✅ Use Safe Field
public function transferMoney(...): TransferResult {
    if ($from->getBalance() < $amount) {
        return new TransferResult(false, 'Insufficient funds');
    }
    // ... transfer logic
    return new TransferResult(true, 'Completed');
}
```

### 6. One Action Per Try Block
```php
// ✅ Correct
try {
    $user = $this->userRepository->find($userId);
} catch (UserNotFoundException $e) {
    return new OrderResult(false, 'User not found');
}
```

### 7. Always Log Caught Exceptions
```php
try {
    // Operation
} catch (CustomException $e) {
    $this->logger->error('Failed', [
        'exception' => $e,
        'stack' => $e->getTraceAsString(),
        'context' => $contextData
    ]);
}
```

### 8. Include Context in Logs
Logs must include stack traces and relevant data.

## Summary
1. Never use nested try-catch
2. Catch specific exceptions
3. Use custom exceptions
4. Try-catch only for external interactions
5. Safe Field for business rules
6. One action per try block
7. Always log with context

## Full Details
`.windsurf/rules/error-handling.md`
