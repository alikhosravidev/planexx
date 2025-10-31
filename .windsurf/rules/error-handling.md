---
trigger: manual
---

# Try-Catch and Error Handling

This document outlines our team's standards and best practices for implementing **error handling** and using **try-catch** blocks effectively.

## Core Principles

### 1. No Nested Try-Catch Blocks

**Strictly Prohibited:** Nested **try-catch** blocks are forbidden.

```php
// ❌ Prohibited: Nested try-catch blocks
try {
    try {
        // Operation
    } catch (SomeException $e) {
        // Handle inner
    }
} catch (AnotherException $e) {
    // Handle outer
}

// ✅ Better: Separate methods
public function performOperation(): Result
{
    try {
        $data = $this->fetchData();
        return $this->processData($data);
    } catch (CustomException $e) {
        $this->logger->error('Operation failed', ['exception' => $e]);
        return new Result(false, $e->getMessage());
    }
}
```

### 2. Avoid Catching General Exceptions

**Prohibited:** Catching `Exception`, `Throwable`, etc.

**Recommended:** Custom domain exceptions.

```php
// ❌ Avoid: Catching general exceptions
try {
    // Operation
} catch (Exception $e) {
    // Handle all
}

// ✅ Better: Catch specific custom exceptions
try {
    // Operation
} catch (UserNotFoundException $e) {
    // Handle specific
}
```

### 3. Use Custom Exceptions

Exceptions should inherit from base project exceptions.

### 4. When to Use Try-Catch

Use **try-catch** only for external systems: APIs, files, databases, networks.

```php
// ✅ Appropriate use
public function fetchUserDataFromExternalApi(string $userId): UserData
{
    try {
        $response = $this->apiClient->get("/users/{$userId}");
        return new UserData($response['data']);
    } catch (ApiConnectionException $e) {
        $this->logger->error('API failed', ['exception' => $e, 'userId' => $userId]);
        throw new UserDataFetchException("Failed: {$e->getMessage()}", 0, $e);
    }
}
```

### 5. When Not to Use Try-Catch

Do not use for business rules; use "Safe Field" approach.

```php
// ❌ Avoid for business rules
try {
    if ($from->getBalance() < $amount) {
        throw new InsufficientFundsException();
    }
    // Transfer
} catch (InsufficientFundsException $e) {
    // Handle
}

// ✅ Use Safe Field
public function transferMoney(Account $from, Account $to, float $amount): TransferResult
{
    if ($from->getBalance() < $amount) {
        return new TransferResult(false, 'Insufficient funds');
    }

    $from->withdraw($amount);
    $to->deposit($amount);

    return new TransferResult(true, 'Completed');
}
```

### 6. One Action Per Try Block

Each **try block** should perform only one action.

```php
// ❌ Multiple actions
try {
    $user = $this->userRepository->find($userId);
    $order = $this->orderRepository->create($user, $orderData);
    $this->emailService->sendOrderConfirmation($order);
} catch (Exception $e) {
    // Which failed?
}

// ✅ One action per try
try {
    $user = $this->userRepository->find($userId);
} catch (UserNotFoundException $e) {
    return new OrderResult(false, 'User not found');
}
// Similar for others
```

### 7. Always Log Caught Exceptions

Log every caught exception with context.

```php
try {
    // Operation
} catch (CustomException $e) {
    $this->logger->error('Failed', [
        'exception' => $e,
        'stack' => $e->getTraceAsString(),
        'context' => $contextData
    ]);
    // Handle
}
```

### 8. Include Context in Logs

Logs must include stack traces and relevant data.

## Summary

1. Never use nested **try-catch** blocks
2. Avoid catching general exceptions
3. Use custom exceptions from base project exceptions
4. Use **try-catch** only for external interactions
5. Use "Safe Field" for business rules
6. One action per **try block**
7. Always log caught exceptions with context

**Team CTO Guidance:**

> "Proper **error handling** focuses on preventing errors through good design rather than catching them after occurrence."

**Actionable Rule:**
- Review **error handling** during code reviews.

**Further Reading:**
- PHP Exception Handling Best Practices
