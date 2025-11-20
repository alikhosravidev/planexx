# CLAUDE.md

This file provides quick guidance to Claude Code when working with this repository. For detailed documentation, see `.claude/` directory.

## Quick Start
**Docker Environment**: All PHP/Laravel commands MUST run inside Docker:
```bash
docker exec planexx_app php artisan [command]
docker exec planexx_app composer [command]
```

**Common Commands**:
```bash
composer setup     # Full setup
composer dev       # Start all services
composer test      # Run tests
./standards/scripts/pint.sh  # Format code
```

📘 **Full Guide**: `.claude/quick-start.md`

## Architecture
**Laravel 12** with **Hybrid Modular Architecture** and **DDD principles**.

- **Core Modules** (`app/Core/`): User, Organization, FormEngine, BPMS
- **Pluggable Modules** (`Modules/`): Notification

📘 **Full Guide**: `.claude/architecture.md`

## Documentation Structure

### Domain Layer (`.claude/domain/`)
- **Entities**: Eloquent models - keep lean, use `$fillable`
- **Value Objects**: Immutable domain concepts with validation
- **Enums**: Must have `label()` method for Persian labels
- **Collections**: Type-safe custom collections

### Application Layer (`.claude/application/`)
- **DTOs**: Pure data carriers, no business logic, mandatory constructor
- **Mappers**: Transform Request → DTO
- **Services**: Business logic, orchestration, transactions

### Infrastructure (`.claude/infrastructure/`)
- **Repositories**: Data access with Criteria pattern
- **Database**: Migrations (two-phase for foreign keys), Factories
- **Observers**: Model lifecycle events

### Presentation (`.claude/presentation/`)
- **Controllers**: Thin, delegate to services
- **BaseWebController**: API-FIRST foundation for admin panel controllers
- **Web Panel Best Practices**: Rules and guidelines for panel development
- **Transformers**: Pipeline-based with `getVirtualFieldResolvers()`
- **Separation**: Rule of Three - max 3 lines of logic in presentation

### API Design (`.claude/api/`)
- **RESTful**: Resources are nouns, not verbs
- **Sub-resources**: `/posts/{id}/comments`
- **Authentication**: API Key via `Api-Key` header

### Code Quality (`.claude/patterns/`)
- **Coding Standards**: PSR-12, meaningful names, type hints
- **Design Patterns**: Follow standard implementations only

### Error Handling (`.claude/error-handling.md`)
- **No nested try-catch** (prohibited)
- **Catch specific exceptions** only
- **Use Safe Field approach** for business rules

### Events (`.claude/events.md`)
- **Prohibit intra-module** event listeners
- **Use for inter-module** communication only
- **Fire business events** for important occurrences

### Testing (`.claude/testing/`)
- **Unit**: Use `PureUnitTestBase` when possible (fastest)
- **Integration**: Don't mock data layer
- **Execution**: Always use `--stop-on-failure --stop-on-error`

### Features (`.claude/features/`)
- **Sorting**: Implement `SortableEntity`, use `HasSorting` trait

### Guidelines (`.claude/guidelines/`)
- **Comments**: Explain WHY, not WHAT/HOW
- **Docker**: All commands via `docker exec planexx_app`

## Common Patterns

### Repository Pattern with Criteria
```php
class UserRepository extends BaseRepository {
    public array $fieldSearchable = ['mobile', 'email'];
    public array $sortableFields = ['id', 'created_at'];
    public function model(): string { return User::class; }
}

// Usage
$user = $repo->pushCriteria(new UserIdentifierCriteria($mobile))->first();
```

### Service Providers
```php
public function register(): void {
    $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
}

public function boot(): void {
    $this->loadRoutesFrom(ProviderUtility::corePath('User/Routes/V1/routes.php'));
    $this->loadMigrationsFrom(ProviderUtility::corePath('User/Database/Migrations'));
}
```

### Transformer System
```php
class UserTransformer extends BaseTransformer {
    protected function getVirtualFieldResolvers(): array {
        return [
            'full_name' => fn($user) => $user->first_name . ' ' . $user->last_name,
        ];
    }
}

// Usage
$transformer = app(TransformerFactory::class)->makeFromRequest(UserTransformer::class, $request);
$result = $transformer->transformModel($user);
```

### BaseWebController (API-FIRST Admin Panel)
```php
// PRIMARY: Use Axios for all CRUD operations
// resources/js/admin/users.js
axios.post('/users', formData)
  .then(response => {
    showNotification('User created successfully');
    refreshTable();
  })
  .catch(error => {
    if (error.response?.status === 422) {
      displayValidationErrors(error.response.data.errors);
    }
  });

// SECONDARY: BaseWebController only for view rendering
class UserManagementController extends BaseWebController {
    public function index(Request $request): View {
        // Fetch data for initial page render
        $response = $this->apiGet('users', [
            'filter' => ['is_active' => 1],
            'sort' => '-created_at',
            'per_page' => 15,
        ]);

        return view('admin.users.index', ['users' => $response['data']]);
    }
    
    public function create(): View {
        $roles = $this->apiGet('roles');
        return view('admin.users.create', ['roles' => $roles['data']]);
    }
    
    // NO store/update/destroy methods - Axios handles these
}
```

## Critical Rules to Remember

1. ✅ **Always use `$fillable`**, never `$guarded` in models
2. ✅ **Hardcode enum values** in migrations (never reference enum classes)
3. ✅ **Two-phase approach** for adding foreign keys to existing tables
4. ✅ **No nested try-catch blocks** (strictly prohibited)
5. ✅ **No intra-module event listeners** (only inter-module)
6. ✅ **DTOs have no methods** except `toArray()` if Arrayable
7. ✅ **Controllers delegate** to services, stay thin
8. ✅ **Admin panel:** Use Axios for CRUD operations, BaseWebController for view rendering only
9. ✅ **Web panel:** All data access ONLY via API - ZERO direct DB access
10. ✅ **Web panel:** NO Service/Repository/Eloquent calls - strictly forbidden
11. ✅ **Run commands inside Docker**: `docker exec planexx_app ...`

## Recent Important Changes
- **Implemented BaseWebController**: API-FIRST foundation for admin panel with internal API forwarding
- **Refactored Transformer system**: No more magic methods, use `getVirtualFieldResolvers()`
- **Moved ValueObjects**: From `app/Domain/` to `app/Bus/`
- **Split HasJobPosition**: Into `HasCreator`, `HasManager`, `HasOwner`, `HasUser`
- **Renamed FormWizard**: To `FormEngine`

## When Working on Specific Tasks

| Task | Read This |
|------|-----------|
| Creating new module | `.claude/architecture.md` |
| Working with models | `.claude/domain/entities.md` |
| Creating DTOs | `.claude/application/dtos.md` |
| Writing services | `.claude/application/services.md` |
| Building APIs | `.claude/api/design.md` |
| Building admin panel | `.claude/presentation/base-web-controller.md` |
| Web panel guidelines | `.claude/presentation/web-panel-best-practices.md` |
| Writing tests | `.claude/testing/guide.md` |
| Database migrations | `.claude/infrastructure/database.md` |
| Error handling | `.claude/error-handling.md` |

## Documentation Index
- **Quick Start**: `.claude/quick-start.md`
- **Architecture**: `.claude/architecture.md`
- **Domain**: `.claude/domain/` (entities, value-objects, enums, collections)
- **Application**: `.claude/application/` (dtos, mappers, services)
- **Infrastructure**: `.claude/infrastructure/` (repositories, database, observers)
- **Presentation**: `.claude/presentation/` (controllers, transformers, separation, base-web-controller, web-panel-best-practices)
- **API**: `.claude/api/` (design, basics, authentication)
- **Patterns**: `.claude/patterns/` (coding-standards, design-patterns)
- **Testing**: `.claude/testing/` (guide, execution)
- **Features**: `.claude/features/` (sorting)
- **Guidelines**: `.claude/guidelines/` (comments, docker)
- **Examples**: `.claude/examples/` (base-web-controller-usage)

## Original Documentation Sources
- Detailed guides: `.windsurf/rules/`
- Additional docs: `docs/` (transformer-usage, migration-guide, sorting)

---

**Note**: This CLAUDE.md file is now a concise index. All detailed documentation has been organized into `.claude/` directory for better Context Window management. When working on specific tasks, refer to the relevant `.claude/` file for detailed guidance.
