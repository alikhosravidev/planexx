# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

### Setup & Installation
```bash
composer setup          # Full setup: install deps, copy .env, generate key, migrate, build assets
composer install        # Install PHP dependencies
npm install            # Install JavaScript dependencies
php artisan migrate    # Run database migrations
```

### Development Server
```bash
composer dev           # Starts all services concurrently: server, queue, logs, vite
# Runs: artisan serve + queue:listen + pail + npm run dev
```

### Testing
```bash
composer test                              # Run all tests with cleared config
php artisan test                          # Run PHPUnit test suite
php artisan test --filter=UserTest        # Run specific test
php artisan test --testsuite=Unit         # Run only unit tests
php artisan test --testsuite=Integration  # Run only integration tests
php artisan test --testsuite=Feature      # Run only feature tests

# Parallel testing (faster)
./standards/scripts/parallel.sh
vendor/bin/paratest
```

### Code Quality
```bash
./standards/scripts/pint.sh              # Run Laravel Pint code formatter
./standards/scripts/check-imports.sh     # Check for unused imports
./standards/hooks/install-hooks.sh       # Install git pre-commit and pre-push hooks
```

### Build & Assets
```bash
npm run dev            # Run Vite dev server with hot reload
npm run build          # Build production assets
```

## Architecture Overview

This is a **Laravel 12** application using a **hybrid modular architecture** with Domain-Driven Design principles.

### Modular Structure

#### Core Modules (`app/Core/`)
Core business domains that are always loaded:
- **User** - Authentication (OTP/Password), user management, addresses
- **Organization** - Departments, job positions, organizational structure
- **FormEngine** - Dynamic form builder and submission system
- **BPMS** - Business Process Management with workflows and tasks

Registered statically in `AppServiceProvider::registerCoreProviders()`.

#### Pluggable Modules (`Modules/`)
Optional feature modules that can be enabled/disabled:
- **Notification** - Notification system

Discovered dynamically via `FilesystemModuleDiscovery` and tracked in `bootstrap/modules.php`.

### Internal Module Structure

Each module follows a consistent DDD-inspired layered architecture:

```
Module/
├── Entities/              # Eloquent Models
├── DTOs/                  # Data Transfer Objects (readonly classes)
├── ValueObjects/          # Domain values with validation (Email, Mobile, etc.)
├── Enums/                 # PHP Enums for constants
├── Repositories/          # Data access with Criteria pattern
│   └── Criteria/          # Query building specifications
├── Services/              # Business logic layer
├── Http/
│   ├── Controllers/       # API/Web controllers
│   ├── Requests/          # Form validation requests
│   ├── Rules/             # Custom validation rules
│   └── Transformers/      # Response transformation
├── Events/                # Domain events
├── Listeners/             # Event listeners
├── Observers/             # Model lifecycle observers
├── Database/
│   ├── Migrations/
│   └── Factories/
├── Routes/
│   └── V1/                # Versioned API routes
├── Providers/             # ServiceProvider + EventServiceProvider
├── Mappers/               # DTO <-> Entity mapping
├── Traits/                # Reusable behaviors
├── Contracts/             # Module interfaces
└── Tests/                 # Unit/Integration/Feature tests
```

### Cross-Module Communication

**Bus Pattern** (`app/Bus/`)
- `MorphHandler` routes entity types to appropriate repositories
- Shared DTOs, ValueObjects (Email, Mobile, UserId, etc.)
- Enables polymorphic operations across modules

**Shared Abstractions** (`app/Contracts/`)
- `BaseRepository` - CRUD operations with Criteria pattern
- `BaseController` / `APIBaseController`
- `BaseTransformer` - Response transformation pipeline
- `BaseModel` - Eloquent model base
- `SortableEntity` - Sorting interface

## Key Patterns & Conventions

### Repository Pattern with Criteria

Repositories provide searchable and sortable data access:

```php
class UserRepository extends BaseRepository {
    public array $fieldSearchable = ['mobile', 'email', 'full_name'];
    public array $sortableFields = ['id', 'created_at'];

    public function model(): string {
        return User::class;
    }
}

// Usage with Criteria
$user = $repo->pushCriteria(new UserIdentifierCriteria($mobile))->first();
```

Repository interfaces should be bound in module ServiceProviders:
```php
$this->app->bind(UserRepositoryInterface::class, UserRepository::class);
```

### Value Objects

Domain values are immutable, validated, and type-safe:

```php
final readonly class Mobile {
    public function __construct(public string $value) {
        // Validates Iranian phone format (09xxxxxxxxx)
        if (!preg_match(CustomValidator::MOBILE_REGEX, $this->value)) {
            throw new InvalidArgumentException();
        }
    }
}
```

Shared value objects are in `app/Bus/ValueObjects/`.

### Data Transfer Objects (DTOs)

DTOs are readonly classes for data exchange:

```php
final readonly class AddressDTO implements Arrayable {
    public function __construct(
        public int $userId,
        public string $address,
        public ?string $postalCode = null,
    ) { }

    public function toArray(): array { /* ... */ }
}
```

### Transformer System

**Modern pipeline-based transformers** (refactored from magic methods):

```php
class UserTransformer extends BaseTransformer {
    protected function getVirtualFieldResolvers(): array {
        return [
            'full_name' => fn($user) => $user->first_name . ' ' . $user->last_name,
            'avatar_url' => fn($user) => Storage::url($user->avatar),
        ];
    }
}

// Usage (RECOMMENDED)
$transformer = app(TransformerFactory::class)->makeFromRequest(UserTransformer::class, $request);
$result = $transformer->transformModel($user);      // Single model
$result = $transformer->transformCollection($users); // Collection
$result = $transformer->transformArray($data);       // Array
```

**Key points:**
- Use `TransformerFactory` for instantiation
- `makeFromRequest()` handles includes/excludes from query params
- Virtual fields defined as closures, not magic methods
- Strict typing: `transformModel()`, `transformCollection()`, `transformArray()`

See `docs/transformer-usage.md` and `docs/migration-guide.md` for full details.

### Service Providers

Each Core module has a `ServiceProvider`:

```php
class UserServiceProvider extends ServiceProvider {
    public function register(): void {
        // Bind interfaces to implementations
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->singleton(OTPGenerator::class, RealGenerator::class);
    }

    public function boot(): void {
        // Load module resources using ProviderUtility helper
        $this->loadRoutesFrom(ProviderUtility::corePath('User/Routes/V1/Admin/routes.php'));
        $this->loadMigrationsFrom(ProviderUtility::corePath('User/Database/Migrations'));
        $this->loadTranslationsFrom(ProviderUtility::corePath('User/Resources/lang'), 'user');
    }
}
```

Each module also has a nested `EventServiceProvider` for event-listener mappings.

### Sortable Models

Models can implement automatic ordering:

```php
use App\Contracts\Sorting\SortableEntity;
use App\Traits\HasSorting;

class YourModel extends Model implements SortableEntity {
    use HasSorting;

    public function sortingColumnName(): string {
        return 'order'; // or custom column
    }
}

// Usage
YourModel::ordered()->get();
```

See `docs/sorting.md` for full documentation (in Persian).

### Model Observers

Use observers for model lifecycle hooks:

```php
class UserBeforeCommitObserver {
    public function creating(User $user): void { /* ... */ }
    public function updating(User $user): void { /* ... */ }
}
```

Register in `EventServiceProvider` or `AppServiceProvider`.

## Testing

### Test Organization

Tests are organized in three levels:
- **Unit**: `./app/Tests/Unit`, `./app/Core/*/Tests/Unit`, `./Modules/*/Tests/Unit`
- **Integration**: `./app/Tests/Integration`, `./app/Core/*/Tests/Integration`, `./Modules/*/Tests/Integration`
- **Feature**: `./app/Tests/Feature`, `./app/Core/*/Tests/Feature`, `./Modules/*/Tests/Feature`

### Test Naming

- Test files end with `Test.php`
- Place tests parallel to source code structure
- Examples: `UserRepositoryTest.php`, `AuthResponseTest.php`

### Test Database

- Uses array cache and sync queue by default
- Test database can be SQLite in-memory or separate test database
- Seeders: `DatabaseSeeder`, `TestDatabaseSeeder`

### Parallel Testing

For faster test execution:
```bash
./standards/scripts/parallel.sh
vendor/bin/paratest
```

Prepare parallel databases with:
```bash
php artisan test:prepare-parallel
```

## Code Conventions

### Strict Types
All PHP files use `declare(strict_types=1);` at the top.

### Path Helpers
Use `ProviderUtility::corePath()` for Core module paths:
```php
ProviderUtility::corePath('User/Routes/V1/routes.php')
// Returns: app_path('Core/User/Routes/V1/routes.php')
```

### Value Object Validation
Domain values validate themselves in constructors:
```php
final readonly class Mobile {
    public function __construct(public string $value) {
        if (!preg_match(CustomValidator::MOBILE_REGEX, $this->value)) {
            throw new InvalidArgumentException('Invalid mobile number');
        }
    }
}
```

### Dependency Injection
Favor constructor injection and interface binding:
```php
public function __construct(
    private readonly UserRepositoryInterface $userRepository,
    private readonly OTPGenerator $otpGenerator,
) { }
```

### Configuration via Services
Use configuration objects instead of magic values:
```php
$this->app->singleton(OTPConfig::class, function () {
    return new OTPConfig(
        enabled: config('services.auth.otp.enabled'),
        codeLength: (int) config('services.auth.otp.code_length'),
        expiresInMinutes: (int) config('services.auth.otp.expires_in_minutes'),
    );
});
```

## Important Notes

### Recent Refactoring (c52ec30)
- Moved ValueObjects from `app/Domain/` to `app/Bus/`
- Split `HasJobPosition` trait into specific traits: `HasCreator`, `HasManager`, `HasOwner`, `HasUser`
- Renamed `FormWizard` module to `FormEngine`

### Transformer Migration
The transformer system was recently refactored from magic methods to explicit pipelines. When working with transformers:
- DO NOT use magic `add*` methods
- DO use `getVirtualFieldResolvers()` returning callables
- DO use `TransformerFactory` for instantiation
- DO use strict types: `transformModel()`, `transformArray()`, `transformCollection()`

See `docs/migration-guide.md` for complete migration details.

### Authentication
Multiple auth strategies via tagged services:
- `OTP` authentication (SMS-based)
- `Password` authentication
- Configured via `services.auth.otp.*` config

### AI Image Service
Multi-provider AI image generation:
- Providers: OpenAI, Stability AI, Google Gemini, Seedream
- Factory pattern via `AIImageServiceFactory`

## Directory Structure Quick Reference

```
app/
├── Core/                   # Core domain modules (User, Organization, FormEngine, BPMS)
├── Bus/                    # Cross-module communication (DTOs, ValueObjects, MorphHandler)
├── Contracts/              # Global abstractions (BaseRepository, BaseTransformer, etc.)
├── Services/               # Global services (Transformer, AIImageService, ModuleManager)
├── Providers/              # AppServiceProvider
├── Utilities/              # Helpers (ProviderUtility, CustomValidator, PriceFormatter)
├── Traits/                 # Global reusable traits
├── Commands/               # Artisan commands
└── Tests/                  # Global test utilities

Modules/
└── Notification/           # Pluggable notification module

config/                     # Laravel configuration files
database/                   # Shared migrations, factories, seeders
docs/                       # Documentation (transformer-usage.md, migration-guide.md, sorting.md)
resources/                  # Views, CSS, JS
routes/                     # Global routes
standards/                  # Code quality tools
├── formatter/              # Pint configuration
├── hooks/                  # Git hooks (pre-commit, pre-push)
└── scripts/                # Quality scripts (pint.sh, parallel.sh, check-imports.sh)
tests/                      # Global test setup
```
