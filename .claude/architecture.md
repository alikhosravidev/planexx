# Architecture Overview

## Technology Stack
- **Laravel 12** with **Hybrid Modular Architecture**
- **Domain-Driven Design** principles
- **Repository Pattern** with Criteria
- **Service Layer** for business logic

## Modular Structure

### Core Modules (`app/Core/`)
Always-loaded business domains:
- **User** - Authentication (OTP/Password), user management, addresses
- **Organization** - Departments, job positions, structure
- **FormEngine** - Dynamic form builder and submissions
- **BPMS** - Business Process Management

Registered statically in `AppServiceProvider::registerCoreProviders()`.

### Pluggable Modules (`Modules/`)
Optional features that can be enabled/disabled:
- **Notification** - Notification system

Discovered via `FilesystemModuleDiscovery`, tracked in `bootstrap/modules.php`.

## Internal Module Structure

```
Module/
├── Entities/              # Eloquent Models
├── DTOs/                  # Data Transfer Objects
├── ValueObjects/          # Domain values with validation
├── Enums/                 # PHP Enums
├── Repositories/          # Data access with Criteria
│   └── Criteria/          # Query specifications
├── Services/              # Business logic
├── Http/
│   ├── Controllers/       # API/Web controllers
│   ├── Requests/          # Form validation
│   ├── Rules/             # Custom validation
│   └── Transformers/      # Response transformation
├── Events/                # Domain events
├── Listeners/             # Event listeners
├── Observers/             # Model lifecycle
├── Database/
│   ├── Migrations/
│   └── Factories/
├── Routes/
│   └── V1/                # Versioned API routes
├── Providers/             # ServiceProvider + EventServiceProvider
├── Mappers/               # DTO <-> Entity mapping
├── Traits/                # Reusable behaviors
├── Contracts/             # Interfaces
└── Tests/                 # Unit/Integration/Feature tests
```

## Cross-Module Communication

### Bus Pattern (`app/Bus/`)
- `MorphHandler` routes entity types to repositories
- Shared DTOs, ValueObjects (Email, Mobile, UserId, etc.)
- Enables polymorphic operations

### Shared Abstractions (`app/Contracts/`)
- `BaseRepository` - CRUD with Criteria
- `BaseController` / `APIBaseController`
- `BaseTransformer` - Response transformation
- `BaseModel` - Eloquent base
- `SortableEntity` - Sorting interface

## Layered Architecture

```
┌─────────────────────────────────────────┐
│         Presentation Layer              │
│   Controllers, Transformers, Views      │
├─────────────────────────────────────────┤
│         Application Layer               │
│   Services, DTOs, Mappers, Events      │
├─────────────────────────────────────────┤
│           Domain Layer                  │
│  Entities, ValueObjects, Enums, Logic   │
├─────────────────────────────────────────┤
│        Infrastructure Layer             │
│  Repositories, Database, Observers      │
└─────────────────────────────────────────┘
```

## Key Conventions

- **Strict Types**: All PHP files use `declare(strict_types=1);`
- **Path Helpers**: Use `ProviderUtility::corePath()` for Core modules
- **Dependency Injection**: Constructor injection with interfaces
- **Configuration**: Via service objects, not magic values

## Directory Quick Reference

```
app/
├── Core/                   # Core domain modules
├── Bus/                    # Cross-module communication
├── Contracts/              # Global abstractions
├── Services/               # Global services
├── Providers/              # AppServiceProvider
├── Utilities/              # Helpers
├── Traits/                 # Global traits
├── Commands/               # Artisan commands
└── Tests/                  # Global test utilities

Modules/
└── Notification/           # Pluggable modules

docs/                       # Additional documentation
.claude/                    # Organized documentation
.windsurf/rules/            # Original documentation files
```

## Further Reading

- **Domain Layer**: See `.claude/domain/`
- **Application Layer**: See `.claude/application/`
- **Infrastructure**: See `.claude/infrastructure/`
- **Presentation**: See `.claude/presentation/`
- **Modular Development**: `.windsurf/rules/modular-development-guide.md`
